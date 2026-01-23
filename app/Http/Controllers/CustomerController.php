<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customers\CustomerStoreRequest;
use App\Models\Customer;
use App\Services\CustomerManagementService;
use App\Services\RestaurantSalesService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Customer $model)
    {
        return theme_view('customers.index', [
            'customers' => $model->where('restaurant_id', restaurantId())->paginate(50),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('customers.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerStoreRequest $request)
    {
        $requestData = $request->merge(['restaurant_id' => restaurantId()])->all();

        $customer = Customer::create($requestData);

        //if request is ajax
        if ($request->ajax()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index', $customer)->with('success_message', 'Success');
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        // Get the customer or fail if not found
        $customer = Customer::findOrFail($id);

        // Parse the optional date range from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Build the base query
        $ordersQuery = $customer->orders();

        // Apply date filtering if dates are provided
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('order_date', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        // Get the filtered orders
        $orders = $ordersQuery->get();

        // Use the service class to calculate total sales
        $sales = app(RestaurantSalesService::class)->getRestaurantSales($orders);

        //use the service class to get customer metrics
        $metrics =  app(CustomerManagementService::class)->getCustomerMetrics($customer);

        // Return the view with all data
        return theme_view('customers.show', [
            'customer' => $customer,
            'orders' => $orders,
            'sales' => $sales,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'metrics' => $metrics
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return theme_view('customers.form', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        if ($request->hasFile('id_picture_location')) {
            $idPictureLocation = $request->file('id_picture_location');

            // Delete old image if it exists
            if ($customer->id_picture_location) {
                Storage::disk('public')->delete($customer->id_picture_location);
            }

            // Optimize the new image before storing it
            ImageOptimizer::optimize($idPictureLocation->getRealPath());

            // Generate a unique file name
            $newImageName = time() . '_' . $idPictureLocation->getClientOriginalName();

            // Store the new image in the correct directory
            $imagePath = $idPictureLocation->storeAs('hotel/customer-id-images', $newImageName, 'public');

            // Instead of merging, store the new image path separately
            $updateData = $request->except('id_picture_location'); // Get other request data
            $updateData['id_picture_location'] = $imagePath; // Add image path manually
        } else {
            $updateData = $request->all(); // If no image, update normally
        }
        // Update the customer record
        $customer->update($updateData);

        return redirect()->route('customers.show', $customer)->with('success_message', 'Customer Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->orders()->exists()) {
            return back()->with('error', 'Customer cannot be deleted because they are linked to orders.');
        }

        $customer->delete();
        return redirect()->route('customers.index')->with('success_message', 'Customer Deleted Successfully');
    }

    public function getCustomerInfo(Request $request)
    {
        $id = $request->id;
        $customer = Customer::find($id);
        return json_encode($customer);
    }

    public function search(Request $request)
    {
        $searchValue = $request->input('search');
        $restaurantId = restaurantId();

        logger()->info('Searching for customers with value: ' . $searchValue);

        // Perform the search based on partial string match
        $query = Customer::where('restaurant_id', $restaurantId);
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('first_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('phone', 'LIKE', "%{$searchValue}%");
            });
        }

        if ($request->ajax()) {
            return response()->json($query->get());
        }

        // Return only the HTML of the updated table rows
        return theme_view('customers.partials.search-results', [
            'customers' => $query->paginate(50),
        ]);
    }

    public function customerTransactions(Customer $customer)
    {
        $transactions = $customer->getCustomerTransactions();
        return theme_view('customers.transactions', [
            'transactions' => $transactions,
            'customer' => $customer
        ]);
    }

    public function getCustomerUnsettledTransactions(Customer $customer)
    {
        $transactions = $customer->getCustomerUnsettledTransactions();
        return theme_view('customers.unsettled-transactions', [
            'transactions' => $transactions,
            'customer' => $customer
        ]);
    }

    public function getCustomerTransactions(Customer $customer)
    {
        $customer->load([
            'invoices',
            'restaurantOrders',
            'barOrders',
            'laundrySales',
            'customerWallet.customerWalletTransactions'
        ]);

        $customer_wallet_transactions = $customer->customerWallet ? $customer->customerWallet->customerWalletTransactions : collect();

        $transactions = collect()
            ->merge($customer->invoices)
            ->merge($customer->restaurantOrders)
            ->merge($customer->barOrders)
            ->merge($customer->laundrySales)
            ->merge($customer_wallet_transactions);

        $sortedTransactions = $transactions->sortBy('created_at')->values()->map(function ($transaction) {
            return [
                'transaction_date' => $transaction instanceof App\Models\CustomerWalletTransaction ? $transaction->transaction_date : $transaction->created_at->format('Y-m-d'),
                'model_type' => class_basename($transaction),
                'id' => $transaction->id,
                'reservation_id' => $transaction->reservation->id ?? null,
                'room_name' => $transaction->room->name ?? null,
                'venue_name' => $transaction->venue->name ?? null,
                'items' => method_exists($transaction, 'getItems') ? $transaction->getItems() : '',
                'description' => $transaction->description ?? '',
                'mode_of_payment' => $transaction->mode_of_payment ?? '',
                'amount' => $transaction->amount,
                'balance' => $transaction->balance ?? '',
                'payments' => $transaction->payments ? $transaction->payments->map(function ($payment) {
                    return [
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                    ];
                })->toArray() : [],
                'settlements' => $transaction->settlements ? $transaction->settlements->map(function ($settlement) {
                    return [
                        'amount' => $settlement->amount,
                    ];
                })->toArray() : [],
            ];
        });

        return response()->json([
            'transactions' => $sortedTransactions->values()->all() // Converts collection to array
        ], 200);
    }

    public function lastDelivery(Customer $customer)
    {
        $lastOrder = $customer->orders()
            ->whereNotNull('delivery_area_id')
            ->latest()
            ->first();

        if (!$lastOrder) {
            return response()->json([
                'status' => 'empty'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'delivery_area_id'   => $lastOrder->delivery_area_id,
            'delivery_area_name' => optional($lastOrder->deliveryArea)->name,
            'delivery_address'   => $lastOrder->delivery_address,
        ]);
    }
}
