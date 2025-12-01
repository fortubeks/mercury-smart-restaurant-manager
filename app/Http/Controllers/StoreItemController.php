<?php

namespace App\Http\Controllers;

use App\Exports\StoreItemsExport;
use App\Http\Requests\StoreItems\StoreItemStoreRequest;
use App\Http\Requests\StoreItems\StoreItemUpdateRequest;
use App\Imports\StoreItemImport;
use App\Imports\StoreItemUpdateImport;
use App\Models\Outlet;
use App\Models\OutletItemMigration;
use App\Models\OutletItemMigrationDetails;
use App\Models\OutletStoreItem;
use App\Models\Store;
use App\Models\StoreIssue;
use App\Models\StoreIssueStoreItem;
use App\Models\StoreItem;
use App\Models\StoreItemActivity;
use App\Models\StoreStoreItem;
use App\Services\PurchaseStoreService;
use App\Services\StoreItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StoreItemController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->input('store_id');

        $baseQuery = StoreItem::query();

        if (!empty($storeId)) {
            $baseQuery->where('store_id', $storeId);
        }
        $storeItems = $baseQuery->get();
        return theme_view('store-items.index', [
            'storeItems' => $storeItems,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('store-items.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemStoreRequest $request)
    {
        $image = [];
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('restaurant/store_items');
            $image['image'] = $imagePath;
        }
        $data = $request->validated();
        $data['store_id'] = restaurant()->defaultStore->id;
        $data['code'] = generateUniqueItemCode();
        $data['qty'] = $request->qty;
        $data['restaurant_id'] = restaurantId();
        $data = array_merge($data, $image);

        $storeItem = StoreItem::create($data);

        //sync the store item with store_store_items table
        $storeItem->stores()->syncWithoutDetaching($storeItem->store_id);

        //if qty is more than 0 create new purchase record
        if ($data['qty'] > 0) {
            (new PurchaseStoreService)->create([
                'store_id' => $data['store_id'],
                'restaurant_id' => $data['restaurant_id'],
                'purchase_date' => now()->toDateString(),
                'sub_total' => 0,
                'total_amount' => 0,
                'items' => [[
                    'store_item_id' => $storeItem->id,
                    'qty' => $data['qty'],
                    'received' => $data['qty'],
                    'rate' => 0,
                    'sub_total' => 0 * $data['qty'],
                    'total_amount' => 0 * $data['qty'],
                    'unit_qty' => 0,
                    'description' => 'Initial stock on creation'
                ]]
            ]);
        }


        return redirect()->route('store-items.index')->with('success_message', 'Store item added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($storeItemId, Request $request, StoreItemService $storeItemService)
    {
        // Get filter values from the request
        $startDate = $request->input('start_date', date('Y-m-d', strtotime('-1 month')));
        $endDate = $request->input('end_date', date('Y-m-d'));
        //use start and end date to get a string like Last 1 month
        $period = getDateDifferenceInFineFormat($startDate, $endDate);

        $storeItem = StoreItem::findOrFail($storeItemId);
        //get revenue for the store item from StorItemService
        $revenueAndQuantitySoldMetrics = $storeItemService->getRevenueAndQuantitySoldMetrics($storeItemId, $startDate, $endDate);
        $storeItem->revenue = $revenueAndQuantitySoldMetrics['revenue'];
        $storeItem->quantity_sold = $revenueAndQuantitySoldMetrics['qty'];
        $purchase = $storeItemService->getPurchase($storeItemId, $startDate, $endDate);
        $storeItem->purchase = $purchase;
        $storeItem->profit = $storeItem->revenue - $purchase;
        $storeItem->profit_margin = $storeItem->revenue > 0
            ? round(($storeItem->profit / $storeItem->revenue) * 100, 0)
            : 0;
        $dailyStats = $storeItemService->getInventoryActivity($storeItem, $startDate, $endDate);
        $storeItemActivities = $storeItem->activities()
            ->whereBetween('activity_date', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();
        //$stockBalance = count($dailyStats) ? $dailyStats[array_key_last($dailyStats)]['balance'] : 0;
        $stockBalance = $storeItem->total_qty;

        return theme_view('store-items.show', [
            'store_item' => $storeItem,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'period' => $period,
            'dailyStats' => array_reverse($dailyStats),
            'storeItemActivities' => $storeItemActivities,
            'stockBalance' => $stockBalance,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return theme_view('store-items.form', [
            'storeItem' => StoreItem::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreItemUpdateRequest $request, $id)
    {
        $storeItem = StoreItem::findOrFail($id);
        $image = [];
        // Check if a new image file has been uploaded
        if ($request->hasFile('image')) {
            $newImageName = $request->file('image')->getClientOriginalName();

            // Check if an image with the same filename already exists
            $existingImage = StoreItem::where('image', $newImageName)->exists();

            if (!$existingImage) {
                // Store the new image
                $imagePath = $request->file('image')->store('restaurant/store_items', 'public');
                // Update the image path in the request data
                $image['image'] = $imagePath;
            } else {
                // Use the existing image
                $image['image'] = $storeItem->image;
            }
        }

        // Update the store item with the merged request data
        //$storeItem->update(array_merge($request->all(), $image));
        $data = (array_merge($request->validated(), $image));
        $storeItem->update($data);

        return redirect()->route('store-items.index')->with('success_message', 'Store item updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreItem $store_item)
    {
        // check if a record exists in outlet_store_items, return back if it does
        $outletStoreItem = OutletStoreItem::where('store_item_id', $store_item->id)->exists();
        if ($outletStoreItem) {
            return redirect()->back()->with('error_message', 'Failed to delete store item. Store Item is used in other transactions.');
        }
        DB::beginTransaction();
        try {
            $store_item->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', 'Failed to delete store item. Store Item is used in other transactions.');
        }

        return redirect()->route('store-items.index')->with('success_message', 'Store Item Deleted Successfully');
    }

    public function search(Request $request)
    {
        $searchValue = $request->input('search');

        // Perform the search based on partial string match
        $storeItems = StoreItem::where('store_id', auth()->user()->restaurant->store->id)->where('name', 'LIKE', "%$searchValue%")->paginate(50);

        // Return only the HTML of the updated table rows
        return theme_view('store-items.search-results', [
            'storeItems' => $storeItems,
        ]);
    }

    public function viewImportItemsForm()
    {
        //return form
        $stores  = restaurant()->stores;
        return theme_view('store-items.import')->with(compact('stores'));
    }

    public function downloadSampleExcel()
    {
        $file = storage_path('sample-import-items.csv'); // Replace 'sample.xlsx' with the path to your sample Excel file
        $headers = [
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'sample-import-items.csv', $headers);
    }

    public function importItems(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
            'store_id' => 'required|exists:stores,id'
        ]);

        $file = $request->file('file');

        $import = new StoreItemImport($request->store_id);
        Excel::import($import, $file);

        $importedItemCount = $import->importedItemCount;
        $errors = $import->getErrors();

        if (!empty($errors)) {
            return redirect()->back()
                ->with('error_message', 'Some rows were not imported due to errors: ' . implode(', ', $errors));
        }

        return redirect('store-items')->with('success_message', $importedItemCount . ' Items imported successfully');
    }

    public function importItemsByUpdate(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $file = $request->file('file');

        $import = new StoreItemUpdateImport();
        Excel::import($import, $file);

        $importedItemCount = $import->importedItemCount;
        $errors = $import->getErrors();

        if (!empty($errors)) {
            return redirect()->back()
                ->with('success', 'Success. However, Some rows were not updated due to errors: ' . implode(', ', $errors));
        }
        return redirect('store-items')->with('success_message', $importedItemCount . ' Items imported successfully');
    }

    public function viewGiveItemsForm(Request $request)
    {
        // Get the category ID from request or default
        $categoryId = $request->category_id ?? null;

        // Get the store ID from request or default
        $storeId = $request->store_id ?? restaurant()->defaultStore->id;

        // Build query conditionally
        $query = StoreItem::query();

        // Filter by category if provided
        if ($categoryId) {
            $query->where('item_category_id', $categoryId);
        }

        // Filter items that exist in store_store_items for given store
        $query->whereHas('stores', function ($q) use ($storeId) {
            $q->where('store_store_items.store_id', $storeId);
        });

        // Eager load pivot data
        $storeItems = $query->with(['stores' => function ($q) use ($storeId) {
            $q->where('store_store_items.store_id', $storeId);
        }])->get();

        return theme_view('store-items.give')->with('storeItems', $storeItems);
    }

    public function giveItems(Request $request)
    {
        // Validate the request data
        $request->validate([
            'recipient' => 'required|string|max:255',
            'outlet_id' => 'required|exists:outlets,id',
            'store_id' => 'required|exists:stores,id'
        ]);

        // Retrieve the recipient's name from the request
        $recipient = $request->input('recipient');

        // Retrieve the quantities of items going out from the request
        $quantities = $request->input('quantities');
        // Filter out entries with null values
        $quantities = array_filter($quantities, function ($value) {
            return $value !== null;
        });

        $store_id = $request->input('store_id');

        $type = $request->input('category_id')
            ? \App\Models\StoreItemCategory::find($request->input('category_id'))?->name ?? 'Other'
            : 'Other';

        // Start a database transaction
        DB::beginTransaction();

        try {
            $store_issue = StoreIssue::create([
                'recipient_name' => $recipient,
                'note' => $request->note,
                'outlet_id' => $request->outlet_id,
                'user_id' => auth()->id(),
                'store_id' => $store_id,
                'type' => $type
            ]);
            // Loop through the quantities and update the items accordingly
            foreach ($quantities as $itemId => $quantity) {
                $store_item = StoreItem::findOrFail($itemId); // Find the item by its ID
                $store_store_item = StoreStoreItem::where('store_id', $store_id)->where('store_item_id', $itemId)->first(); // Find the item by its ID

                //get previous store qty
                $previous_qty = $store_store_item->qty;

                // Check if there are enough items in stock
                if ($previous_qty < $quantity) {
                    // Rollback the transaction if there's not enough stock
                    DB::rollBack();

                    // Redirect back with error message
                    return redirect()->back()->with('error', 'Not enough ' . $store_item->name . ' in stock.');
                }

                $new_qty = $previous_qty - $quantity;

                // Update the quantity of the store store item
                $store_item->stores()->updateExistingPivot($store_id, ['qty' => $new_qty]);

                // Create a transaction record for giving out the item
                StoreIssueStoreItem::create([
                    'store_item_id' => $store_item->id,
                    'qty' => $quantity,
                    'store_id' => $store_id,
                    'store_issue_id' => $store_issue->id,
                    // You may add more fields to the transaction record as needed
                ]);

                //create a record for receiving the item
                $outlet_store_item = OutletStoreItem::firstOrCreate(
                    [
                        'store_item_id' => $store_item->id,
                        'outlet_id' => $store_issue->outlet_id,
                    ],
                    [
                        'qty' => 0,
                        'price' => $store_item->selling_price ? $store_item->selling_price : 0,
                    ]
                );

                $outlet_store_item->qty += $quantity;
                $outlet_store_item->save();

                //save in StoreItem activity table
                StoreItemActivity::create([
                    'store_item_id' => $store_item->id,
                    'qty' => $quantity,
                    'store_id' => $store_id,
                    'store_issue_id' => $store_issue->id,
                    'previous_qty' => $previous_qty,
                    'activity_date' => $store_issue->created_at,
                    'current_qty' => $new_qty,
                    'description' => 'Give out'
                ]);
            }

            // If all operations succeed, commit the transaction
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success_message', 'Items successfully given out to ' . $recipient);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction and handle the error
            DB::rollBack();

            // Log the error message
            Log::error($e);

            // Redirect back with error message
            return redirect()->back()->with('error_message', 'Failed to give out items. ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new StoreItemsExport, 'store_items.xlsx');
    }

    public function reportDamagedItem(Request $request)
    {
        $request->validate([
            'store_item_id' => 'required|exists:store_items,id',
            'date' => 'required',
            'qty' => 'required|numeric|min:1',
            'outlet_id' => 'nullable|exists:outlets,id',
            'reason' => 'required|string|max:255',
        ]);

        $itemId = $request->store_item_id;
        $outletId = $request->outlet_id;
        $quantity = $request->qty;
        $storeId = auth()->user()->restaurant->store->id;
        $outlet = Outlet::find($outletId);
        $outletName = $outlet ? $outlet->name : 'Store';
        // Find the item in store_items
        $storeItem = StoreItem::find($itemId);
        //get previous store qty
        $previousQty = $storeItem->qty;

        DB::beginTransaction();
        try {
            if ($outletId) {
                // Find the item in outlet_store_items
                $outletItem = OutletStoreItem::where('store_item_id', $itemId)
                    ->where('outlet_id', $outletId)
                    ->first();

                if (!$outletItem || $outletItem->qty < $quantity) {
                    return redirect()->back()->with('error', 'Item not found in the Outlet or Insufficient stock in outlet.');
                }

                // Reduce quantity in outlet store
                $outletItem->decrement('qty', $quantity);
            } else {
                if (!$storeItem || $storeItem->qty < $quantity) {
                    return redirect()->back()->withErrors([
                        'error' => 'Insufficient stock in main store.',
                    ]);
                }

                // Reduce quantity in main store
                $storeItem->decrement('qty', $quantity);
            }

            // Log the damaged item in store_item_activities
            StoreItemActivity::create([
                'store_item_id' => $itemId,
                'store_id' => $storeId,
                'activity_date' => $request->date,
                'previous_qty' => $previousQty,
                'qty' => $quantity,
                'current_qty' => $storeItem->qty,
                'description' => 'Damaged - ' . $outletName . ' ' . $request->reason,
            ]);
            DB::commit();
        } catch (Exception $ex) {
            //throw $th;
            DB::rollBack();
            logger($ex);
        }

        return back()->with('success', 'Damaged item reported successfully.');
    }
}
