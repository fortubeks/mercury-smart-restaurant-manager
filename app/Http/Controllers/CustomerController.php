<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customers\CustomerStoreRequest;
use App\Models\Customer;
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
    public function show(Customer $customer)
    {
        return theme_view('customers.show', [
            'customer' => $customer
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
    public function update(GuestUpdateRequest $request, Customer $guest)
    {
        if ($request->hasFile('id_picture_location')) {
            $idPictureLocation = $request->file('id_picture_location');

            // Delete old image if it exists
            if ($guest->id_picture_location) {
                Storage::disk('public')->delete($guest->id_picture_location);
            }

            // Optimize the new image before storing it
            ImageOptimizer::optimize($idPictureLocation->getRealPath());

            // Generate a unique file name
            $newImageName = time() . '_' . $idPictureLocation->getClientOriginalName();

            // Store the new image in the correct directory
            $imagePath = $idPictureLocation->storeAs('hotel/guest-id-images', $newImageName, 'public');

            // Instead of merging, store the new image path separately
            $updateData = $request->except('id_picture_location'); // Get other request data
            $updateData['id_picture_location'] = $imagePath; // Add image path manually
        } else {
            $updateData = $request->all(); // If no image, update normally
        }
        // Update the guest record
        $guest->update($updateData);

        return redirect()->route('customers.show', $guest)->with('success_message', 'Customer Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $guest)
    {
        if ($guest->roomReservations()->exists()) {
            return back()->with('error', 'Customer cannot be deleted because they are linked to reservations.');
        }
        if ($guest->invoices()->exists()) {
            return back()->with('error', 'Customer cannot be deleted because they are linked to reservations.');
        }
        if ($guest->barOrders()->exists()) {
            return back()->with('error', 'Customer cannot be deleted because they are linked to reservations.');
        }
        if ($guest->restaurantOrders()->exists()) {
            return back()->with('error', 'Customer cannot be deleted because they are linked to reservations.');
        }
        $guest->delete();
        return redirect()->route('customers.index')->with('success_message', 'Customer Deleted Successfully');
    }

    public function restore(string $id)
    {
        $guest = Customer::withTrashed()->findOrFail($id);
        $guest->restore();
        return redirect()->route('customers.index')->with('success', 'Customer restored successfully.');
    }

    public function getGuestInfo(Request $request)
    {
        $id = $request->id;
        $guest = Customer::find($id);
        return json_encode($guest);
    }

    public function search(Request $request)
    {
        $searchValue = $request->input('search');

        // Perform the search based on partial string match
        $query = Customer::where('restaurant_id', restaurantId());
        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where(function ($q) use ($searchValue) {
                    $q->where('first_name', 'LIKE', "%$searchValue%")
                        ->orWhere('last_name', 'LIKE', "%$searchValue%");
                })
                    ->orWhere('phone', 'LIKE', "%$searchValue%");
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

    public function guestTransactions(Customer $guest)
    {
        $transactions = $guest->getGuestTransactions();
        return theme_view('customers.transactions', [
            'transactions' => $transactions,
            'guest' => $guest
        ]);
    }

    public function getGuestUnsettledTransactions(Customer $guest)
    {
        $transactions = $guest->getGuestUnsettledTransactions();
        return theme_view('customers.unsettled-transactions', [
            'transactions' => $transactions,
            'guest' => $guest
        ]);
    }

    public function getGuestTransactions(Customer $guest)
    {
        $guest->load([
            'invoices',
            'restaurantOrders',
            'barOrders',
            'laundrySales',
            'guestWallet.guestWalletTransactions'
        ]);

        $guest_wallet_transactions = $guest->guestWallet ? $guest->guestWallet->guestWalletTransactions : collect();

        $transactions = collect()
            ->merge($guest->invoices)
            ->merge($guest->restaurantOrders)
            ->merge($guest->barOrders)
            ->merge($guest->laundrySales)
            ->merge($guest_wallet_transactions);

        $sortedTransactions = $transactions->sortBy('created_at')->values()->map(function ($transaction) {
            return [
                'transaction_date' => $transaction instanceof App\Models\GuestWalletTransaction ? $transaction->transaction_date : $transaction->created_at->format('Y-m-d'),
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
}
