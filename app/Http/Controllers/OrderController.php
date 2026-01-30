<?php

namespace App\Http\Controllers;

use App\Http\Requests\Orders\OrderStoreRequest;
use App\Models\DailySale;
use App\Models\DeliveryArea;
use App\Models\DeliveryRider;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemOrder;
use App\Models\Order;
use App\Models\State;
use App\Services\IncomingPaymentService;
use App\Services\OrderItemService;
use App\Services\RestaurantCartService;
use App\Services\RestaurantSalesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request, RestaurantSalesService $restaurantSalesService, RestaurantCartService $restaurantCartService)
    {
        // Authorize the user
        //$this->authorize('viewAny', $model);
        $user = auth()->user();
        $currentShift = $user->current_shift ?? now()->format('Y-m-d');
        $outletId = $user->outlet_id;

        $cartOrders = $restaurantCartService->getRestaurantCartOrders();

        // Fetch orders with optimized query, reusing loaded relationships
        $orders = Order::with('customer', 'menuItems')->where('outlet_id', $user->outlet_id)
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
        $menuItems = MenuItem::with('preparedStock')->where('outlet_id', $outletId)->where('is_available', true)->orderBy('name', 'asc')->get();
        $menuCategories = MenuCategory::with('menuItems')->where('outlet_id', $outletId)->orderBy('name', 'asc')->get();

        //check if there is a sales record for the selected date. so that if there is, hide the submit buttons
        $dailySalesRecord = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $user->current_shift)->first();

        return theme_view('orders.create')->with(compact('menuItems', 'menuCategories', 'outletId', 'orderCartId', 'dailySalesRecord'));
    }

    public function show(Order $order)
    {
        $paymentStatus = $order->getPaymentStatus();
        $order = $order->load(['customer', 'payments', 'menuItems', 'createdBy']);
        $order->paymentStatus = $paymentStatus['status'];
        $order->totalPayments = $paymentStatus['total_payments'];
        $order->amountDue = $paymentStatus['amount_due'];
        $availableRiders = DeliveryRider::where('status', 'active')->get();

        return theme_view('orders.show')->with(compact('order', 'availableRiders'));
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
        $customer_id = $cart[$orderCartId]['order_info']['customer_id'] ?? null;
        $delivery_address = $cart[$orderCartId]['order_info']['delivery_address'] ?? null;
        $delivery_rider_id = $cart[$orderCartId]['order_info']['delivery_rider_id'] ?? null;
        $delivery_fee = $cart[$orderCartId]['order_info']['delivery_fee'] ?? 0;
        $total_amount += $delivery_fee;

        $deliveryAreaId = $cart[$orderCartId]['order_info']['delivery_area_id'] ?? null;

        $delivery_area_id = $deliveryAreaId
            ? $this->getDeliveryArea($deliveryAreaId)
            : null;

        $request->merge([
            'total_amount' => $total_amount,
            'sub_total' => $sub_total,
            'customer_id' => $customer_id,
            'tax_amount' => $tax_amount,
            'date_of_payment' => $orderDate,
            'delivery_address' => $delivery_address,
            'delivery_rider_id' => $delivery_rider_id,
            'delivery_area_id' => $delivery_area_id,
            'delivery_fee' => $delivery_fee,
            'created_by' => auth()->id(),
            'outlet_id' => auth()->user()->outlet_id,
        ]);

        // Start a transaction
        DB::beginTransaction();

        try {

            $orderData = $request->all();

            // Create the order
            $order = Order::create($orderData);

            // Retrieve the items from the cart order
            $items = $cart[$orderCartId]['items'];

            $orderItemService->saveItemsAndUpdateStock($order, $items);

            //utilise the payment service to store the incoming payment if payment is not credit
            if ($request->payment_method != 'credit') {
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
            } else {
                $order->status = 'unsettled';
                $order->save();
            }

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
            return redirect('/cart/edit?id=' . $orderCartId)->withErrors([
                'error' => 'An error occurred. Please try again.',
            ]);
        }
    }

    private function getDeliveryArea($delivery_area_name)
    {
        $areaName = trim($delivery_area_name);

        // Find existing area by case-insensitive match (to avoid duplicates)
        $existingArea = DeliveryArea::whereRaw('LOWER(name) = ?', [strtolower($areaName)])->first();

        if ($existingArea) {
            $deliveryAreaId = $existingArea->id;
        } else {
            // Create new area if none exists
            $state = State::where('name', 'Rivers')->where('country_id', 161)->first();
            $newArea = DeliveryArea::create(['state_id' => $state->id, 'name' => $areaName]);
            $deliveryAreaId = $newArea->id;
        }
        return $deliveryAreaId;
    }

    public function addOrderPayment(Request $request, IncomingPaymentService $paymentService)
    {
        $request->validate([
            'date_of_payment' => 'required',
            'mode_of_payment' => 'required',
            'amount' => 'required',
            'order_id' => 'required'
        ]);

        $order = Order::findOrFail($request->order_id);
        $wallet = null;

        if ($request->mode_of_payment == "wallet") {
            $customer = $order->customer;
            $payer = $customer;

            $wallet = getPayerWallet($payer, $order);
        }

        //check if mode of payment is wallet, if wallet, check if wallet is well funded, return back with error if not
        if ($request->mode_of_payment == "wallet" && $request->amount > $wallet->balance) {
            $walletOwner = $wallet->customer ? $wallet->customer : $wallet->company;
            return back()->with('error', 'Insufficient balance to deduct from ' . $walletOwner->name() . ' wallet.');
        }

        if ($request->amount > $order->amountDue()) {
            return back()->with('error', 'Amount to pay is greater than amount outstanding. Please try again.');
        }
        DB::beginTransaction();
        try {
            $payable_type = get_class($order);
            $payable_id = $order->id;

            //if it is different day settlement, add under settlement table
            //else delete credit sale and add normal payment
            if ($request->has('settlement')) {
                $settlement = new Settlement();
                $settlement->payable_id = $order->id;
                $settlement->payable_type = get_class($order);
                $settlement->restaurant_id = restaurantId();
                $settlement->customer_id = $order->customer_id;
                $settlement->company_id = $order->company_id;
                $settlement->amount = $request->amount;
                $settlement->shift_date = $request->date_of_payment;

                $settlement->save();

                $payable_type = Settlement::class;
                $payable_id = $settlement->id;
            } else {
                eraseCreditPayment($order);
            }

            $paymentService->processPayment(
                [
                    'payment_method' => $request->payment_method,
                    'amount' => $request->amount,
                    'payable_type' => get_class($order),
                    'payable_id' => $order->id,
                    'restaurant_id' => restaurantId(),
                    'description' => 'Payment for Order #' . $order->reference,
                    'bank_account_id' => $request->bank_account_id,
                    'date_of_payment' => $request->date_of_payment
                ]
            );


            DB::commit();
        } catch (\Exception $e) {
            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => 'An error occurred. ' . $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Payment added successfully');
    }

    public function destroy(Order $order, OrderItemService $orderItemService)
    {
        //authorize
        //$this->authorize('delete', $order);
        $daily_sales_record = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $order->order_date)->first();
        if ($daily_sales_record) {
            return back()->with('error', 'Delete failed. Audit has already been done.');
        }
        DB::beginTransaction();
        try {
            foreach ($order->payments as $payment) {
                if ($payment->payment_method == 'wallet') {
                    $customer_wallet = $order->customer->customerWallet;
                    $customer_wallet->balance += $payment->amount;
                    $customer_wallet->save();
                }

                $payment->delete();
            }
            foreach ($order->settlements as $settlement) {
                if ($settlement->payment_method == 'wallet') {
                    $customer_wallet = $order->customer->customerWallet;
                    $customer_wallet->balance += $payment->amount;
                    $customer_wallet->save();
                    customerWalletTransaction::create([
                        'customer_wallet_id' => $customer_wallet->id,
                        'amount' => $payment->amount,
                        'transaction_type' => 'credit',
                        'description' => 'Refund from deleting order on ' . $order->order_date,
                    ]);
                }
                $settlement->delete();
            }
            // Restore stock
            $orderItemService->restoreItemsAndStock($order);

            // Delete order items
            $order->menuItems()->detach();

            // Delete the order
            $order->delete();
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

        return redirect('orders')->with('success', 'Deleted successfully');
    }

    public function assignRider(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rider_id' => 'required|exists:delivery_riders,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->delivery_rider_id = $request->rider_id;
        $order->save();

        // Reload the order with the new rider relationship
        $order->load('deliveryRider');

        // Return updated partial view
        $html = theme_view('orders.partials.order-rider-section', [
            'order' => $order,
            'availableRiders' => DeliveryRider::where('status', 'active')->get()
        ])->render();

        return response()->json(['html' => $html]);
    }
}
