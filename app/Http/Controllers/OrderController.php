<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\RestaurantCartService;
use App\Services\RestaurantSalesService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Order $model, RestaurantSalesService $restaurantSalesService, RestaurantCartService $restaurantCartService)
    {
        // Authorize the user
        //$this->authorize('viewAny', $model);
        $user = auth()->user();
        $currentShift = $user->current_shift ?? now()->format('Y-m-d');
        $outletId = $user->outlet_id;

        // Eager load outlet to avoid multiple calls
        //$user->load(['outlet']);

        $restaurantCartOrders = $restaurantCartService->getRestaurantCartOrders();

        // Fetch orders with optimized query, reusing loaded relationships
        $orders = $model::with('customer', 'menuItems')->where('outlet_id', $user->outlet_id)
            ->where('order_date', $currentShift)
            ->get();

        $totalAmount = $orders->sum('total_amount');

        // Pass the orders to service class for sales calculations
        $sales = $restaurantSalesService->getRestaurantSales($orders);

        // Return view with compact data
        return theme_view('orders.index')
            ->with(compact('orders', 'restaurantCartOrders', 'totalAmount', 'sales', 'currentShift', 'outletId'));
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
        $outlet_id = userRestaurantOutlet()->id;

        $restaurant_cart_order_id = Carbon::now()->timestamp;
        $items = RestaurantItem::where('outlet_id', $outlet_id)->where('is_available', true)->orderBy('name', 'asc')->get();

        //check if there is a sales record for the selected date. so that if there is, hide the submit buttons
        $daily_sales_record = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $user->current_shift)->first();

        return view('dashboard.restaurant-orders.create')->with(compact('items', 'outlet_id', 'restaurant_cart_order_id', 'daily_sales_record'));
    }

    public function show(RestaurantOrder $restaurant_order)
    {
        $total_payments = $restaurant_order->totalPayments();
        $amount_due = $restaurant_order->amountDue();
        return view('dashboard.restaurant-orders.show')->with(compact('restaurant_order', 'total_payments', 'amount_due'));
    }


    public function store(OrderStoreRequest $request)
    {
        // Retrieve the restaurant cart order ID from the request
        $restaurantCartOrderId = $request->input('restaurant_cart_order_id');

        // Retrieve the current cart items and options from the session
        $cart = session()->get('restaurant-order-cart', []);
        if (!array_key_exists($restaurantCartOrderId, $cart)) {
            return back()->withErrors([
                'error' => 'The restaurant cart was not found',
            ]);
        }

        //check if there is a sales record for the selected date. so that if there is, return back with error
        $orderDate = $request->order_date;
        $daily_sales_record = DailySale::where('restaurant_id', restaurantId())->where('shift_date', $orderDate)->exists();
        if ($daily_sales_record) {
            return back()->with('error', 'Order cannot be saved. Audit has already been done.');
        }

        $total_amount = $cart[$restaurantCartOrderId]['order_info']['total_amount'];
        $sub_total = $cart[$restaurantCartOrderId]['order_info']['sub_total'];
        $tax_amount = $cart[$restaurantCartOrderId]['order_info']['tax_amount'];
        $room_id = $cart[$restaurantCartOrderId]['order_info']['room_id'] ?? '';
        $customer_id = $cart[$restaurantCartOrderId]['order_info']['customer_id'] ?? null;
        $charge_company = $cart[$restaurantCartOrderId]['order_info']['charge_company'] ?? null;

        //if room is selected get customer id, if customer is selected, no need
        if ($room_id != '' && $customer_id == null) {
            $room = Room::find($room_id);
            if (!$room) {
                return back()->with('error', 'Error with Room Selection');
            }
            $customer_id = $room->getOccupiedcustomer()->id;
        }

        if ($charge_company == 1) {
            //get the customer company_id and attahc tot he request
            $customer = customer::findOrFail($customer_id);
            if (!$customer->company_id) {
                return redirect('restaurant-cart/edit?id=' . $restaurantCartOrderId)->withErrors([
                    'error' => 'Selected customer does not belong to a company',
                ]);
            }
            $request->merge(['company_id' => $customer->company_id]);
        }

        $request->merge([
            'total_amount' => $total_amount,
            'customer_id' => $customer_id,
            'room_id' => $room_id,
            'tax_amount' => $tax_amount,
            'date_of_payment' => $request->order_date
        ]);

        //if the payment is credit, ensure there is a customer selected
        if ($request->cwp == 'yes' || $request->mode_of_payment == 'wallet') {
            if (!$request->customer_id || $request->customer_id == null) {
                return redirect('restaurant-cart/edit?id=' . $restaurantCartOrderId)->withErrors(['customer_id' => 'customer selection is required for credit or wallet transactions.']);
            }
        }
        //dd($request->all());
        // Start a transaction
        DB::beginTransaction();

        try {
            // Retrieve the restaurant cart from the session
            $restaurantCart = session()->get('restaurant-order-cart', []);

            // Check if the restaurant cart order ID exists in the session
            if (!array_key_exists($restaurantCartOrderId, $restaurantCart)) {
                throw new \Exception('Restaurant cart order not found in session');
            }

            //get customer id from room number selected or from the customer no in the restaurant order cart in session

            // Retrieve the restaurant order details from the request
            $restaurantOrderData = $request->only([
                'outlet_id',
                'restaurant_id',
                'user_id',
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
                'company_id'
            ]);

            // Create the restaurant order
            $restaurantOrder = RestaurantOrder::create($restaurantOrderData);
            $restaurantOrder->update(['amount' => $sub_total]);

            //if room is selected and customer_id is null, get the customer lodged in that room
            if ($restaurantOrder->customer_id == '' && $request->room_id != '') {
                $customer_id = RoomReservation::where('room_id', $request->room_id)->latest()->value('customer_id');
                if ($customer_id) {
                    $restaurantOrder->customer_id = $customer_id;
                }
            }

            // Retrieve the items from the restaurant cart order
            $items = $restaurantCart[$restaurantCartOrderId]['items'];

            // Loop through each item and create restaurant order outlet store items
            foreach ($items as $itemId => $item) {
                $restaurantOrderItemData = [
                    'restaurant_order_id' => $restaurantOrder->id,
                    'restaurant_item_id' => $itemId,
                    'qty' => $item['quantity'],
                    'amount' => $item['amount'],
                    'tax_rate' => calculateTaxRate(),
                    'tax_amount' => $item['tax'],
                    'discount_rate' => 0,
                    'discount_type' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $item['total'],
                ];

                // Create the restaurant order outlet store item
                RestaurantOrderItem::create($restaurantOrderItemData);

                if (restaurant()->appSetting->restaurant_manage_stock) {
                    $restaurant_item = RestaurantItem::with(['components.outletStoreItem', 'outletStoreItems'])->find($itemId);

                    if ($restaurant_item->is_combo) {
                        // For combo items, deduct inventory based on components
                        foreach ($restaurant_item->components as $component) {
                            $outletStoreItem = $component->outletStoreItem;
                            if ($outletStoreItem) {
                                $outletStoreItem->qty -= $item['quantity'] * $component->pivot->quantity_used;
                                $outletStoreItem->save();
                            }
                        }
                    } elseif ($restaurant_item->outletStoreItems->isNotEmpty()) {
                        // For regular items linked to multiple outlet store items via pivot
                        foreach ($restaurant_item->outletStoreItems as $storeItem) {
                            $storeItem->qty -= $item['quantity'] * $storeItem->pivot->quantity_used;
                            $storeItem->save();
                        }
                    } else {
                        // If a direct relationship (e.g., belongsTo one outlet store item) exists
                        $outletStoreItem = $restaurant_item->outletStoreItem ?? null;

                        if ($outletStoreItem) {
                            $outletStoreItem->qty -= $item['quantity'];
                            $outletStoreItem->save();
                        }
                    }
                }
            }

            $wallet = null;
            if ($request->mode_of_payment == "wallet") {
                $customer = $restaurantOrder->customer;

                $payer = $customer;
                $wallet = getPayerWallet($payer, $restaurantOrder);
            }


            $payment_controller = new PaymentController();
            $payment_controller->addPayment(
                $request,
                get_class($restaurantOrder),
                $restaurantOrder->id,
                $total_amount,
                $wallet,
                $restaurantOrder->restaurant_id,
                'Restaurant Order Payment'
            );
            // Commit the transaction
            DB::commit();

            // Clear the restaurant cart from the session
            session()->forget('restaurant-order-cart.' . $restaurantCartOrderId);

            return redirect('restaurant-orders')->with('success', 'Order saved successfully');
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
                    $restaurant_item = $item->restaurantItem;

                    if (!$restaurant_item) continue;

                    if ($restaurant_item->is_combo == 1) {
                        // Combo item: restore inventory for each component
                        foreach ($restaurant_item->components as $component) {
                            $outlet_store_item = $component->outletStoreItem;
                            if ($outlet_store_item) {
                                $outlet_store_item->qty += $item->quantity * $component->pivot->quantity_used;
                                $outlet_store_item->save();
                            }
                        }
                    } elseif ($restaurant_item->outletStoreItems && $restaurant_item->outletStoreItems->isNotEmpty()) {
                        // Regular item with multiple linked ingredients (via pivot)
                        foreach ($restaurant_item->outletStoreItems as $outletStoreItem) {
                            $outletStoreItem->qty += $item->quantity * $outletStoreItem->pivot->quantity_used;
                            $outletStoreItem->save();
                        }
                    } elseif ($restaurant_item->outletStoreItem) {
                        // Regular item directly linked to one outlet store item
                        $outlet_store_item = $restaurant_item->outletStoreItem;
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
