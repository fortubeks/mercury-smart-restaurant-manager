<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseStoreItem;
use App\Models\StoreItem;
use App\Models\StoreItemActivity;
use App\Services\OutgoingPaymentService;
use App\Services\PurchaseStoreService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Purchase $model)
    {
        $store_id = restaurant()->defaultStore->id;

        $metrics = Cache::remember("purchaseMetrics_{$store_id}", 600, function () {
            return $this->getPurchasesMetrics();
        });
        $purchases = $model->where('store_id', $store_id)->orderBy('purchase_date', 'desc')->limit(30)->get();

        return theme_view('purchases.index', [
            'purchases' => $purchases,
            'metrics' => $metrics
        ]);
    }

    public function showAllPurchases()
    {
        $store_id = restaurant()->defaultStore->id;

        return theme_view('purchases.all-purchases', [
            'purchases' => Purchase::where('store_id', $store_id)->orderBy('purchase_date', 'desc')->get(),
        ]);
    }

    public function getPurchasesMetrics()
    {
        $metrics = [
            'today' => 0,
            'this_week' => 0,
            'this_month' => 0,
            'avg_weekly' => 0,
            'topMonthlyPurchaseItems' => [],
            'topYearlyPurchaseItems' => [],
        ];

        // Get the start and end of the current month
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        // Assuming we consider the purchases of the last 4 weeks to calculate the average
        $fourWeeksAgo = Carbon::now()->copy()->subWeeks(4)->startOfWeek()->toDateString();

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Get the start and end of the current year
        $startOfYear = Carbon::now()->startOfYear()->toDateString();
        $endOfYear = Carbon::now()->endOfYear()->toDateString();

        // Get the date 6 months ago
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth()->toDateString();

        $store_id = restaurant()->defaultStore->id;

        // Calculate purchases for today
        $metrics['today'] = Purchase::whereDate('purchase_date', Carbon::now()->toDateString())
            ->where('store_id', $store_id) // Assuming you want to filter by the current user's restaurant
            ->sum('total_amount');

        // Calculate purchases for this week
        $metrics['this_week'] = Purchase::whereBetween('purchase_date', [
            $startOfWeek,
            $endOfWeek
        ])->where('store_id', $store_id)
            ->sum('total_amount');

        // Calculate purchases for this month
        $metrics['this_month'] = Purchase::whereBetween('purchase_date', [
            $startOfMonth,
            $endOfMonth
        ])->where('store_id', $store_id)
            ->sum('total_amount');

        // Calculate average weekly purchases

        $totalLastFourWeeks = Purchase::whereBetween('purchase_date', [
            $fourWeeksAgo,
            $endOfWeek
        ])->where('store_id', $store_id)
            ->sum('total_amount');

        $metrics['avg_weekly'] = $totalLastFourWeeks / 4;

        // Top 5 purchase items for the month
        $topMonthlyPurchaseItems = PurchaseStoreItem::select('purchase_store_items.store_item_id', DB::raw('SUM(purchase_store_items.total_amount) as total_amount'), DB::raw('SUM(purchase_store_items.qty) as total_count'))
            ->join('purchases', 'purchase_store_items.purchase_id', '=', 'purchases.id') // Joining Purchase table
            ->where('purchases.store_id', $store_id)
            ->whereBetween('purchases.purchase_date', [$startOfMonth, $endOfMonth]) // Corrected column reference to purchases
            ->groupBy('purchase_store_items.store_item_id')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // Top 5 purchase items for the year
        $topYearlyPurchaseItems = PurchaseStoreItem::select('purchase_store_items.store_item_id', DB::raw('SUM(purchase_store_items.total_amount) as total_amount'), DB::raw('SUM(purchase_store_items.qty) as total_count'))
            ->join('purchases', 'purchase_store_items.purchase_id', '=', 'purchases.id') // Joining Purchase table
            ->where('purchases.store_id', $store_id)
            ->whereBetween('purchases.purchase_date', [$startOfYear, $endOfYear]) // Corrected column reference to purchases
            ->groupBy('purchase_store_items.store_item_id')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // If you want to include the names of the purchase items (assuming you have a relationship with an PurchaseItem model):
        $topMonthlyPurchaseItems = $topMonthlyPurchaseItems->load('storeItem');
        $topYearlyPurchaseItems = $topYearlyPurchaseItems->load('storeItem');

        $metrics['topMonthlyPurchaseItems'] = $topMonthlyPurchaseItems;
        $metrics['topYearlyPurchaseItems'] = $topYearlyPurchaseItems;

        // Query to get total purchases for each month in the last 6 months
        $purchasesLastSixMonths = Purchase::select(
            DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month'),
            DB::raw('SUM(total_amount) as total_amount')
        )
            ->where('store_id', $store_id)
            ->where('purchase_date', '>=', $sixMonthsAgo)
            ->groupBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m")'))
            ->get();

        // Format the data for easier consumption (optional)
        $formattedPurchases = [];
        foreach ($purchasesLastSixMonths as $purchase) {
            $formattedPurchases[] = [
                'month' => $purchase->month,
                'total_amount' => $purchase->total_amount,
            ];
        }
        //dd($metrics);
        // Now you can return or use the $metrics array as needed
        return $metrics;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('purchases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Purchase $purchase)
    {
        $request->validate([
            'purchase_date' => 'required',
            'category_id' => 'required',
        ]);
        $amount = 0;
        foreach ($request->amount as $_amount) {
            $amount += $_amount;
        }
        $store_id = restaurant()->defaultStore->id;
        //add tax (if any) to amount to get total amount

        // Start a database transaction
        DB::beginTransaction();

        try {
            $purchase = (new PurchaseStoreService)->create([
                'store_id' => $store_id,
                'restaurant_id' => restaurantId(),
                'purchase_date' => $request->purchase_date,
                'supplier_id' => $request->supplier_id,
                'sub_total' => $amount,
                'total_amount' => $amount,
                'status' => $request->status,
                'note' => $request->note,
                'category_id' => $request->category_id,
                'items' => collect($request->store_items)->map(function ($store_item_id, $key) use ($request) {
                    return [
                        'store_item_id' => $store_item_id,
                        'qty' => $request->qty[$key],
                        'received' => $request->received[$key],
                        'rate' => $request->rate[$key],
                        'sub_total' => $request->amount[$key],
                        'total_amount' => $request->amount[$key],
                        'unit_qty' => $request->unit_qty[$key],
                    ];
                })->toArray()
            ]);

            if ($request->hasFile('uploaded_file')) {
                $file = $request->file('uploaded_file');
                $filePath = $file->store('public/images/purchase-files');
                // Save the file base path to the purchase record
                $purchase->file_path = basename($filePath);
                $purchase->save();
            }

            //Handle payment
            if ($request->payment_amount) {
                $validatedPaymentRequest = $request->validate([
                    'payment_amount' => 'required|numeric',
                    'payment_method' => 'required|string',
                    'date_of_payment' => 'required',
                    'bank_account_id' => 'required'
                ]);
                $data = array_merge($validatedPaymentRequest, [
                    'restaurant_id' => restaurantId(),
                    'purchase_id' => $purchase->id,
                    'amount' => $request->payment_amount
                ]);

                (new OutgoingPaymentService)->createForPurchase($data);
            }

            // If all operations succeed, commit the transaction
            DB::commit();

            return redirect()->route('purchases.index')->with('success_message', 'Purchase Created Successfully');
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction and handle the error
            DB::rollBack();

            // Log the error message
            Log::error($e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error_message', 'Something went wrong. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return theme_view('purchases.show', [
            'purchase' => $purchase,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        // Form validation
        $request->validate([
            'purchase_date' => 'required',
            'category_id' => 'required',
        ]);

        $amount = 0;
        foreach ($request->amount as $_amount) {
            $amount += $_amount;
        }
        foreach ($request->new_item_amount as $_new_amount) {
            $amount += $_new_amount;
        }

        DB::beginTransaction();
        try {
            $purchase->update([
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
                'note' => $request->note,
                'sub_total' => $amount,
                'total_amount' => $amount,
                'item_category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id ?? $purchase->supplier_id,
            ]);

            // Handle existing purchase items
            foreach ($request->store_item as $key => $store_item) {
                if ($request->purchase_store_item_id[$key] === null) {
                    continue;
                }
                $purchase_store_item = PurchaseStoreItem::find($request->purchase_store_item_id[$key]);
                $old_received = $purchase_store_item->received;
                $purchase_store_item->update([
                    'qty' => $request->qty[$key],
                    'received' => $request->received[$key],
                    'rate' => $request->rate[$key],
                    'amount' => $request->amount[$key],
                    'total_amount' => $request->amount[$key],
                    'unit_qty' => $request->unit_qty[$key]
                ]);
                $store_item = $purchase_store_item->storeItem;
                $previous_qty = $store_item->qty;

                $store_item->qty -= $old_received;
                $store_item->qty += $request->received[$key];
                $store_item->save();

                //save in StoreItem activity table
                StoreItemActivity::create([
                    'purchase_id' => $purchase->id,
                    'store_item_id' => $store_item->id,
                    'store_id' => $purchase->store_id,
                    'qty' => $request->qty[$key],
                    'previous_qty' => $previous_qty,
                    'activity_date' => $purchase_store_item->updated_at,
                    'current_qty' => $store_item->qty,
                    'description' => 'Update'
                ]);
            }

            // Handle new purchase items
            foreach ($request->new_item as $key => $new_item) {
                if ($request->new_item[$key] === null) {
                    continue;
                }

                $item = StoreItem::find($new_item);

                PurchaseStoreItem::create([
                    'purchase_id' => $purchase->id,
                    'store_item_id' => $item->id,
                    'store_id' => $purchase->store_id,
                    'restaurant_id' => auth()->user()->restaurant_id,
                    'qty' => $request->new_item_qty[$key],
                    'received' => $request->new_item_received[$key],
                    'rate' => $request->new_item_rate[$key],
                    'amount' => $request->new_item_amount[$key],
                    'total_amount' => $request->new_item_amount[$key],
                    'unit_qty' => $request->new_item_unit_qty[$key]
                ]);

                $item->qty += $request->new_item_received[$key];
                $item->save();

                //save in StoreItem activity table
                StoreItemActivity::create([
                    'purchase_id' => $purchase->id,
                    'store_item_id' => $item->id,
                    'store_id' => $purchase->store_id,
                    'qty' => $request->new_item_received[$key],
                    'previous_qty' => $previous_qty,
                    'activity_date' => $purchase->purchase_date,
                    'current_qty' => $store_item->qty,
                    'description' => 'Purchase'
                ]);
            }

            // Handle file upload if applicable
            if ($request->hasFile('uploaded_file')) {
                // Process file upload
            }

            DB::commit();
            // Redirect back with a success message
            return redirect()->back()->with('success_message', 'Purchase Updated Successfully');
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => 'An error occurred. Please contact support',
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //reduce stock
        // Handle existing purchase items
        foreach ($purchase->items as $key => $purchase_item) {
            $store_item = $purchase_item->storeItem;
            $store_item->qty -= $purchase_item->received;
            $store_item->save();
        }
        //delete payments

        //then delete the purchase
        $purchase->delete();
        return redirect('purchases')->with('success', 'Purchase deleted successfully');
    }

    public function viewSummary(Request $request)
    {
        // Get the current month start and end date
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $store_id = restaurant()->defaultStore->id;

        // Query to get purchases categorized and summed up for the selected date range
        $purchases = Purchase::where('store_id', $store_id)
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->selectRaw('item_category_id, SUM(total_amount) as total_amount')
            ->groupBy('item_category_id')
            ->with('category') // Assuming there's a relationship to get the category name
            ->get();

        $totalPurchases = $purchases->sum('total_amount');

        return theme_view('purchases.summary', compact('purchases', 'startDate', 'endDate', 'totalPurchases'));
    }

    public function getPurchaseItems(Request $request)
    {
        $store_id = restaurant()->defaultStore->id;
        $purchases = Purchase::where('store_id', $store_id)
            ->where('item_category_id', $request->category_id)->get();

        foreach ($purchases as $purchase) {
            $purchase->getItems = $purchase->getItems();
        }

        return response()->json($purchases);
    }

    public function showUnpaidPurchases()
    {
        $storeId = restaurant()->defaultStore->id;

        $purchases = Purchase::where('store_id', $storeId)
            ->whereRaw('(SELECT COALESCE(SUM(amount),0)
                     FROM outgoing_payments
                     WHERE outgoing_payments.payable_id = purchases.id
                     AND outgoing_payments.payable_type = "App\\Models\\Purchase") < purchases.total_amount')
            ->orderBy('purchase_date', 'desc')
            ->get();

        return theme_view('purchases.unpaid-purchases', compact('purchases'));
    }
}
