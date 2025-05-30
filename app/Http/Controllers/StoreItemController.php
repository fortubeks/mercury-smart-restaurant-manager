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
use App\Models\StoreIssue;
use App\Models\StoreIssueStoreItem;
use App\Models\StoreItem;
use App\Models\StoreItemActivity;
use App\Services\PurchaseStoreService;
use App\Services\StoreItemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class StoreItemController extends Controller
{
    public function index(StoreItem $model)
    {
        return theme_view('store-items.index', [
            'storeItems' => $model->where('store_id', restaurant()->defaultStore->id)->get(),
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
        $data = array_merge($data, $image);

        $storeItem = StoreItem::create($data);

        //if qty is more than 0 create new purchase record

        if ($data['qty'] > 0) {
            (new PurchaseStoreService)->create([
                'store_id' => $data['store_id'],
                'restaurant_id' => restaurantId(),
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
        $stockBalance = count($dailyStats) ? $dailyStats[array_key_last($dailyStats)]['balance'] : 0;

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
        return theme_view('store-items.import');
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
            'file' => 'required|mimes:xlsx,csv'
        ]);

        $file = $request->file('file');

        $import = new StoreItemImport();
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
        $type = $request->type;
        $store_id = auth()->user()->restaurant->defaultStore->id;
        if ($type == 'drinks') {
            //get items in store with drinks category
            $items = StoreItem::where('item_category_id', 2)->where('store_id', $store_id)
                ->where('for_sale', true)->get();
            return theme_view('store-items.give-drinks')->with('items', $items);
        }
        if ($type == 'food') {
            //get items in store with drinks category
            $storeItems = StoreItem::where('store_id', $store_id)->get();
            return theme_view('store-items.give-food')->with('storeItems', $storeItems);
        }
        if ($type == 'hk') {
            //get items in store with drinks category
            $items = StoreItem::where('item_category_id', 3)->where('store_id', $store_id)->get();
            return theme_view('store-items.give-hk')->with('items', $items);
        }
        return theme_view('store-items.give');
    }

    public function giveItems(Request $request)
    {
        // Validate the request data
        $request->validate([
            'recipient' => 'required|string|max:255',
            'outlet_id' => 'required',
        ]);

        // Retrieve the recipient's name from the request
        $recipient = $request->input('recipient');

        // Retrieve the quantities of items going out from the request
        $quantities = $request->input('quantities');
        // Filter out entries with null values
        $quantities = array_filter($quantities, function ($value) {
            return $value !== null;
        });

        $store_id = auth()->user()->restaurant->defaultStore->id;

        // Start a database transaction
        DB::beginTransaction();

        try {
            $store_issue = StoreIssue::create([
                'recipient_name' => $recipient,
                'note' => $request->note,
                'outlet_id' => $request->outlet_id,
                'user_id' => auth()->id(),
                'store_id' => $store_id,
                'type' => $request->type
            ]);
            // Loop through the quantities and update the items accordingly
            foreach ($quantities as $itemId => $quantity) {
                $store_item = StoreItem::findOrFail($itemId); // Find the item by its ID

                // Check if there are enough items in stock
                if ($store_item->qty < $quantity) {
                    // Rollback the transaction if there's not enough stock
                    DB::rollBack();

                    // Redirect back with error message
                    return redirect()->back()->with('error', 'Not enough ' . $store_item->name . ' in stock.');
                }

                //get previous store qty
                $previous_qty = $store_item->qty;

                // Update the quantity of the store item
                $store_item->qty -= $quantity;

                $store_item->save();

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
                    'current_qty' => $store_item->qty,
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
            Log::error($e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error_message', 'Failed to give out items. ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new StoreItemsExport, 'store_items.xlsx');
    }

    public function viewMigrateItemsForm(Request $request)
    {
        $type = $request->type;
        $store_id = auth()->user()->restaurant->store->id;

        if ($type == 'drinks' && isset($request->outlet_a) && isset($request->outlet_b)) {
            $outletA = Outlet::findorFail($request->outlet_a);
            $outletB = Outlet::findorFail($request->outlet_b);
            // Step 1: Get the duplicate store_item_ids
            $duplicateStoreItemIds = OutletStoreItem::whereIn('outlet_id', [$outletA->id, $outletB->id])
                ->select('store_item_id')
                ->groupBy('store_item_id')
                ->havingRaw('COUNT(*) > 1')
                ->pluck('store_item_id');

            // Step 2: Join and filter results for outlet_id = 5
            $outletAItems = OutletStoreItem::join('store_items', 'outlet_store_items.store_item_id', '=', 'store_items.id')
                ->whereIn('outlet_store_items.store_item_id', $duplicateStoreItemIds)
                ->where('outlet_store_items.outlet_id', $outletA->id)
                ->where('store_items.store_id', $store_id)
                ->select('outlet_store_items.*', 'store_items.store_id')
                ->get();

            // Step 3: Join and filter results for outlet_id = 6
            $outletBItems = OutletStoreItem::join('store_items', 'outlet_store_items.store_item_id', '=', 'store_items.id')
                ->whereIn('outlet_store_items.store_item_id', $duplicateStoreItemIds)
                ->where('outlet_store_items.outlet_id', $outletB->id)
                ->where('store_items.store_id', $store_id)
                ->select('outlet_store_items.*', 'store_items.store_id')
                ->get()
                ->keyBy('store_item_id'); // Group by store_item_id
            //dd(compact('outletAItems', 'outletBItems'));
            return theme_view('store-items.migrate.step-2')->with(compact('outletAItems', 'outletBItems', 'outletA', 'outletB'));
        }

        $outlets = restaurant()->outlets()->where('type', 'bar')->get();

        return theme_view('store-items.migrate.step-1')->with(compact('outlets'));
    }

    public function migrateItems(Request $request)
    {
        // Validate the request data
        $request->validate([
            'outletA' => 'required',
            'outletB' => 'required',
        ]);

        // Retrieve the quantities of items going out from the request
        $quantities = $request->input('quantities');
        // Filter out entries with null values
        $quantities = array_filter($quantities, function ($value) {
            return $value !== null;
        });

        // Start a database transaction
        DB::beginTransaction();

        try {
            $outletItemMigration = OutletItemMigration::create([
                'note' => $request->note,
                'from_outlet_id' => $request->outletA,
                'to_outlet_id' => $request->outletB,
                'user_id' => auth()->id(),
                'restaurant_id' => restaurantId(),
            ]);
            // Loop through the quantities and update the items accordingly
            foreach ($quantities as $itemId => $quantity) {
                $outletA_item = OutletStoreItem::findOrFail($itemId); // Find the item by its ID
                $storeItem = $outletA_item->storeItem;
                $outletB_item = OutletStoreItem::where('outlet_id', $request->outletB)
                    ->where('store_item_id', $storeItem->id)->first();

                // Check if there are enough items in stock
                if ($outletA_item->qty < $quantity) {
                    // Rollback the transaction if there's not enough stock
                    DB::rollBack();

                    // Redirect back with error message
                    return redirect()->back()->with('error', 'Not enough ' . $outletA_item->storeItem->name . ' in stock.');
                }

                // Update the quantity of the store item
                $outletA_item->qty -= $quantity;
                $outletB_item->qty += $quantity;

                $outletA_item->save();
                $outletB_item->save();

                //save in StoreItem activity table
                OutletItemMigrationDetails::create([
                    'store_item_id' => $outletA_item->storeItem->id,
                    'qty' => $quantity,
                    'outlet_item_migration_id' => $outletItemMigration->id,
                    'outletA_balance' => $outletA_item->qty,
                    'outletB_balance' => $outletB_item->qty
                ]);
            }

            // If all operations succeed, commit the transaction
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success_message', 'Items successfully transfered out to ' . $outletB_item->outlet->name);
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction and handle the error
            DB::rollBack();

            // Log the error message
            Log::error($e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error_message', 'Failed to transfer out items. Please try again later.');
        }
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
