<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\OrderStoreRequest;
use App\Models\DailySale;
use App\Models\MenuItem;
use App\Models\MenuItemOrder;
use App\Models\Order;
use App\Services\IncomingPaymentService;
use App\Services\OrderItemService;
use App\Services\RestaurantCartService;
use App\Services\RestaurantSalesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Order $model, RestaurantSalesService $restaurantSalesService, RestaurantCartService $restaurantCartService)
    {
        // Authorize the user
        //$this->authorize('viewAny', $model);
        $user = auth()->user();
        $currentShift = $user->current_shift ?? now()->format('Y-m-d');
        $outletId = $user->outlet_id;

        $cartOrders = $restaurantCartService->getRestaurantCartOrders();

        // Fetch orders with optimized query, reusing loaded relationships
        $orders = $model::with('customer', 'menuItems')->where('outlet_id', $user->outlet_id)
            ->where('order_date', $currentShift)
            ->get();

        $totalAmount = $orders->sum('total_amount');

        // Pass the orders to service class for sales calculations
        $sales = $restaurantSalesService->getRestaurantSales($orders);

        // Return view with compact data
        return theme_view('orders.index')
            ->with(compact('orders', 'cartOrders', 'totalAmount', 'sales', 'currentShift', 'outletId'));
    }

    public function dashboard()
    {
        $restaurant = restaurant();

        $dailySales = RestaurantOrder::selectRaw('DATE(order_date) as day, SUM(total_amount) as total')
            ->where('restaurant_id', $restaurant->id)
            ->whereDate('order_date', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(order_date)'))
            ->orderBy('day', 'asc')
            ->get();

        $days = $dailySales->pluck('day')->map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('M d'); // e.g., Mar 31
        });

        $salesData = $dailySales->pluck('total');

        return view('dashboard.restaurant-orders.dashboard', compact('days', 'salesData'));
    }

    public function create()
    {
        $user = auth()->user();
        $outletId = $user->outlet_id;

        $orderCartId = Carbon::now()->timestamp;
        $menuItems = MenuItem::where('outlet_id', $outletId)->where('is_available', true)->orderBy('name', 'asc')->get();

        //check if there is a sales record for the selected date. so that if there is, hide the submit buttons
        $dailySalesRecord = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $user->current_shift)->first();

        return theme_view('orders.create')->with(compact('menuItems', 'outletId', 'orderCartId', 'dailySalesRecord'));
    }

    public function show(Order $order)
    {
        $paymentStatus = $order->getPaymentStatus();
        $order = $order->load(['customer', 'payments', 'menuItems', 'createdBy']);
        $order->paymentStatus = $paymentStatus['status'];
        $order->totalPayments = $paymentStatus['total_payments'];
        $order->amountDue = $paymentStatus['amount_due'];

        return theme_view('orders.show')->with(compact('order'));
    }


    public function store(OrderStoreRequest $request, IncomingPaymentService $paymentService, OrderItemService $orderItemService)
    {
        // Retrieve the restaurant cart order ID from the request
        $orderCartId = $request->input('order_cart_id');

        // Retrieve the current cart items and options from the session
        $cart = session()->get('restaurant-order-cart', []);
        if (!array_key_exists($orderCartId, $cart)) {
            return back()->withErrors([
                'error' => 'The cart was not found',
            ]);
        }

        //check if there is a sales record for the selected date. so that if there is, return back with error
        $orderDate = $request->order_date;
        $daily_sales_record = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $orderDate)->exists();
        if ($daily_sales_record) {
            return back()->with('error', 'Order cannot be saved. Audit has already been done.');
        }

        $total_amount = $cart[$orderCartId]['order_info']['total_amount'];
        $sub_total = $cart[$orderCartId]['order_info']['sub_total'];
        $tax_amount = $cart[$orderCartId]['order_info']['tax_amount'];
        $room_id = $cart[$orderCartId]['order_info']['room_id'] ?? '';
        $customer_id = $cart[$orderCartId]['order_info']['customer_id'] ?? null;

        $request->merge([
            'total_amount' => $total_amount,
            'customer_id' => $customer_id,
            'room_id' => $room_id,
            'tax_amount' => $tax_amount,
            'date_of_payment' => $orderDate
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {
            // Retrieve the restaurant order details from the request
            $orderData = $request->only([
                'outlet_id',
                'customer_id',
                'order_date',
                'status',
                'amount',
                'tax_rate',
                'tax_amount',
                'discount_rate',
                'discount_type',
                'discount_amount',
                'total_amount',
                'created_by'
            ]);

            // Create the order
            $order = Order::create($orderData);
            $order->update(['amount' => $sub_total]);

            // Retrieve the items from the cart order
            $items = $cart[$orderCartId]['items'];

            $orderItemService->saveItemsAndUpdateStock($order, $items);

            //utilise the payment service to store the incoming payment
            $paymentService->processPayment(
                [
                    'payment_method' => $request->payment_method,
                    'amount' => $total_amount,
                    'payable_type' => get_class($order),
                    'payable_id' => $order->id,
                    'restaurant_id' => restaurantId(),
                    'description' => 'Payment for Order #' . $order->reference,
                    'bank_account_id' => $request->bank_account_id,
                    'date_of_payment' => $request->date_of_payment
                ]
            );

            // Commit the transaction
            DB::commit();

            // Clear the cart from the session
            session()->forget('restaurant-order-cart.' . $orderCartId);

            return redirect('orders')->with('success', 'Order saved successfully');
        } catch (\Exception $e) {

            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => 'An error occurred. Please try again.',
            ]);
        }
    }

    public function addRestaurantOrderPayment(Request $request)
    {
        $request->validate([
            'mode_of_payment' => 'required',
            'date_of_payment' => 'required',
            'amount' => 'required',
            'restaurant_order_id' => 'required'
        ]);

        $restaurant_order = RestaurantOrder::findOrFail($request->restaurant_order_id);
        $wallet = null;

        if ($request->mode_of_payment == "wallet") {
            $customer = $restaurant_order->customer;
            $payer = $customer;

            $wallet = getPayerWallet($payer, $restaurant_order);
        }

        //check if mode of payment is wallet, if wallet, check if wallet is well funded, return back with error if not
        if ($request->mode_of_payment == "wallet" && $request->amount > $wallet->balance) {
            return back()->with('error', 'Insufficient balance to deduct from.');
        }

        if ($request->amount > $restaurant_order->amountDue()) {
            return back()->withErrors([
                'error' => 'Amount to pay is greater than credit. Please try again.',
            ]);
        }
        DB::beginTransaction();
        try {
            $payable_type = get_class($restaurant_order);
            $payable_id = $restaurant_order->id;

            //if it is different day settlement, add under settlement table
            //else delete credit sale and add normal payment
            if ($request->has('settlement')) {
                $settlement = new Settlement();
                $settlement->payable_id = $restaurant_order->id;
                $settlement->payable_type = get_class($restaurant_order);
                $settlement->restaurant_id = restaurantId();
                $settlement->customer_id = $restaurant_order->customer_id;
                $settlement->company_id = $restaurant_order->company_id;
                $settlement->amount = $request->amount;
                $settlement->shift_date = $request->date_of_payment;

                $settlement->save();

                $payable_type = Settlement::class;
                $payable_id = $settlement->id;
            } else {
                eraseCreditPayment($restaurant_order);
            }
            $payment_controller = new PaymentController();
            $payment_controller->addPayment(
                $request,
                $payable_type,
                $payable_id,
                $request->amount,
                $wallet,
                $restaurant_order->restaurant_id,
                'Restaurant Order Payment'
            );

            DB::commit();
        } catch (\Exception $e) {
            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => 'An error occurred. Please try again.',
            ]);
        }

        return back()->with('success', 'Payment added successfully');
    }



    public function destroy(RestaurantOrder $restaurant_order)
    {
        //authorize
        $this->authorize('delete', $restaurant_order);
        //we have to delete the payments made on this order
        //for each payment on the order, if the payment is wallet or credit add the amount back to the customer wallet balance 
        //then delete the payment, then the wallet transaction will be deleted automatically
        $daily_sales_record = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $restaurant_order->order_date)->first();
        if ($daily_sales_record) {
            return back()->with('error', 'Delete failed. Audit has already been done.');
        }
        DB::beginTransaction();
        try {
            foreach ($restaurant_order->payments as $payment) {
                if ($payment->payment_method == 'wallet') {
                    $customer_wallet = $restaurant_order->customer->customerWallet;
                    $customer_wallet->balance += $payment->amount;
                    $customer_wallet->save();
                }

                $payment->delete();
            }
            foreach ($restaurant_order->settlements as $settlement) {
                if ($settlement->payment_method == 'wallet') {
                    $customer_wallet = $restaurant_order->customer->customerWallet;
                    $customer_wallet->balance += $payment->amount;
                    $customer_wallet->save();
                    customerWalletTransaction::create([
                        'customer_wallet_id' => $customer_wallet->id,
                        'amount' => $payment->amount,
                        'transaction_type' => 'credit',
                        'description' => 'Refund from deleting order on ' . $restaurant_order->order_date,
                    ]);
                }
                $settlement->delete();
            }
            if (restaurant()->appSetting->restaurant_manage_stock == true) {
                foreach ($restaurant_order->items as $item) {
                    $menuItem = $item->restaurantItem;

                    if (!$menuItem) continue;

                    if ($menuItem->is_combo == 1) {
                        // Combo item: restore inventory for each component
                        foreach ($menuItem->components as $component) {
                            $outlet_store_item = $component->outletStoreItem;
                            if ($outlet_store_item) {
                                $outlet_store_item->qty += $item->quantity * $component->pivot->quantity_used;
                                $outlet_store_item->save();
                            }
                        }
                    } elseif ($menuItem->outletStoreItems && $menuItem->outletStoreItems->isNotEmpty()) {
                        // Regular item with multiple linked ingredients (via pivot)
                        foreach ($menuItem->outletStoreItems as $outletStoreItem) {
                            $outletStoreItem->qty += $item->quantity * $outletStoreItem->pivot->quantity_used;
                            $outletStoreItem->save();
                        }
                    } elseif ($menuItem->outletStoreItem) {
                        // Regular item directly linked to one outlet store item
                        $outlet_store_item = $menuItem->outletStoreItem;
                        $outlet_store_item->qty += $item->quantity;
                        $outlet_store_item->save();
                    }
                }
            }
            $restaurant_order->delete();
            DB::commit();
        } catch (\Exception $e) {
            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => 'An error occurred. Please try again.',
            ]);
        }

        return redirect('restaurant-orders')->with('success', 'Deleted successfully');
    }
}
