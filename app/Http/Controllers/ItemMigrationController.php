<?php

namespace App\Http\Controllers;

use App\Models\ItemMigration;
use App\Models\ItemMigrationDetail;
use App\Models\Outlet;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemMigrationController extends Controller
{
    public function viewMigrateItemsForm(Request $request)
    {
        $type = $request->type ?? null;
        $outlets = restaurant()->outlets()->get();
        $stores = restaurant()->stores()->get();

        if ($type && $request->filled(['outlet_a', 'outlet_b'])) {


            if ($type === 'outlet') {
                $outletA = Outlet::findOrFail($request->outlet_a);
                $outletB = Outlet::findOrFail($request->outlet_b);
                // Step 1: Find common store_item_ids
                $duplicateStoreItemIds = OutletStoreItem::whereIn('outlet_id', [$outletA->id, $outletB->id])
                    ->select('store_item_id')
                    ->groupBy('store_item_id')
                    ->havingRaw('COUNT(*) > 1')
                    ->pluck('store_item_id');

                // Step 2: Get outlet A's items
                $outletAItems = OutletStoreItem::join('store_items', 'outlet_store_items.store_item_id', '=', 'store_items.id')
                    ->whereIn('outlet_store_items.store_item_id', $duplicateStoreItemIds)
                    ->where('outlet_store_items.outlet_id', $outletA->id)
                    ->select('outlet_store_items.*', 'store_items.store_id')
                    ->get();

                // Step 3: Get outlet B's items, key by store_item_id
                $outletBItems = OutletStoreItem::join('store_items', 'outlet_store_items.store_item_id', '=', 'store_items.id')
                    ->whereIn('outlet_store_items.store_item_id', $duplicateStoreItemIds)
                    ->where('outlet_store_items.outlet_id', $outletB->id)
                    ->select('outlet_store_items.*', 'store_items.store_id')
                    ->get()
                    ->keyBy('store_item_id');

                return theme_view('store-items.migrate.step-2-outlets')->with(compact(
                    'outletA',
                    'outletB',
                    'outletAItems',
                    'outletBItems'
                ));
            }
            if ($type === 'store') {
                $storeA = Store::findOrFail($request->outlet_a);
                $storeB = Store::findOrFail($request->outlet_b);

                // Load all items with pivot data
                $storeAItems = $storeA->storeItems()->withPivot('qty')->get();
                $storeBItems = $storeB->storeItems()->withPivot('qty')->get()->keyBy('name');

                return theme_view('store-items.migrate.step-2-stores')->with([
                    'storeA' => $storeA,
                    'storeB' => $storeB,
                    'storeAItems' => $storeAItems,
                    'storeBItems' => $storeBItems,
                ]);
            }

            // if ($type === 'store') {
            //     $storeA = Store::findOrFail($request->outlet_a);
            //     $storeB = Store::findOrFail($request->outlet_b);

            //     // Step 1: Find common item names between both stores
            //     $storeAItemNames = StoreItem::where('store_id', $storeA->id)->pluck('name')->toArray();
            //     $storeBItemNames = StoreItem::where('store_id', $storeB->id)->pluck('name')->toArray();

            //     $duplicateItemNames = array_intersect($storeAItemNames, $storeBItemNames);

            //     // Step 2: Get Store A's items with those names
            //     $storeAItems = StoreItem::where('store_id', $storeA->id)
            //         ->whereIn('name', $duplicateItemNames)
            //         ->get();

            //     // Step 3: Get Store B's items with those names, key by name
            //     $storeBItems = StoreItem::where('store_id', $storeB->id)
            //         ->whereIn('name', $duplicateItemNames)
            //         ->get()
            //         ->keyBy('name');

            //     return theme_view('store-items.migrate.step-2-stores')->with([
            //         'storeA' => $storeA,
            //         'storeB' => $storeB,
            //         'storeAItems' => $storeAItems,
            //         'storeBItems' => $storeBItems,
            //     ]);
            // }
        }

        return theme_view('store-items.migrate.step-1')->with(compact('outlets', 'stores'));
    }

    public function migrateItems(Request $request)
    {
        $request->validate([
            'from_type' => 'required|in:store,outlet',
            'to_type' => 'required|in:store,outlet',
            'from_id' => 'required|integer',
            'to_id' => 'required|integer',
            'quantities' => 'required|array',
        ]);

        $quantities = array_filter($request->quantities, fn($val) => $val !== null);

        DB::beginTransaction();

        try {
            $fromClass = $request->from_type === 'store' ? Store::class : Outlet::class;
            $toClass = $request->to_type === 'store' ? Store::class : Outlet::class;

            $fromModel = $fromClass::findOrFail($request->from_id);
            $toModel = $toClass::findOrFail($request->to_id);

            $migration = ItemMigration::create([
                'note' => $request->note,
                'from_id' => $fromModel->id,
                'from_type' => $fromClass,
                'to_id' => $toModel->id,
                'to_type' => $toClass,
                'user_id' => auth()->id(),
                'restaurant_id' => restaurantId(),
            ]);

            foreach ($quantities as $itemId => $quantity) {
                // Get from-item
                $fromItem = $fromModel->storeItems()->wherePivot('id', $itemId)->firstOrFail();

                $storeItem = $fromItem;

                // Get or create to-item
                $toItem = $toModel->storeItems()->where('store_item_id', $storeItem->id)->first();
                if (!$toItem) {
                    $toModel->storeItems()->attach($storeItem->id, ['qty' => 0]);
                    $toItem = $toModel->storeItems()->where('store_item_id', $storeItem->id)->first();
                }

                // Validate stock
                if ($fromItem->pivot->qty < $quantity) {
                    DB::rollBack();
                    return back()->with('error', "Not enough stock of {$storeItem->name}.");
                }

                // Update quantities
                $newFromQty = $fromItem->pivot->qty - $quantity;
                $newToQty = $toItem->pivot->qty + $quantity;

                $fromModel->storeItems()->updateExistingPivot($storeItem->id, ['qty' => $newFromQty]);
                $toModel->storeItems()->updateExistingPivot($storeItem->id, ['qty' => $newToQty]);

                ItemMigrationDetail::create([
                    'item_migration_id' => $migration->id,
                    'store_item_id' => $storeItem->id,
                    'qty' => $quantity,
                    'from_balance' => $newFromQty,
                    'to_balance' => $newToQty,
                ]);
            }

            DB::commit();
            return back()->with('success_message', 'Items migrated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            return back()->with('error_message', 'Migration failed.');
        }
    }
}
