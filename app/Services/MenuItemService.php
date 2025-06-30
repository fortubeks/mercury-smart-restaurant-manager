<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuItemService
{
    public function save(Request $request): ?MenuItem
    {
        return $this->saveOrUpdate($request);
    }

    public function update(Request $request, MenuItem $item): ?MenuItem
    {
        return $this->saveOrUpdate($request, $item);
    }

    private function saveOrUpdate(Request $request, ?MenuItem $item = null): ?MenuItem
    {
        try {
            DB::beginTransaction();

            $requestData = [];

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu-items/images', 'public');
                $requestData['image'] = $imagePath;
            }

            $requestData['is_available'] = $request->has('is_available') ? 1 : 0;
            $requestData['is_combo'] = $request->has('is_combo') ? 1 : 0;

            $categoryId = $request->menu_category_id ??
                MenuCategory::where('outlet_id', $request->outlet_id)->where('is_default', true)->value('id');

            $request->merge(['menu_category_id' => $categoryId]);

            if ($item) {
                $item->update(array_merge($request->all(), $requestData));
            } else {
                $item = MenuItem::create(array_merge($request->all(), $requestData));
            }

            // Sync outlet store items
            $syncData = $this->getSyncDataForOutletStoreItems($request);

            $item->outletStoreItems()->sync($syncData);

            // Sync combo items
            $comboItems = $request->input('combo_items', []);
            $syncData = [];

            foreach ($comboItems as $componentId => $values) {
                if (isset($values['checked']) && $values['checked']) {
                    $syncData[$componentId] = [
                        'qty' => $values['quantity'] ?? 1
                    ];
                }
            }
            $item->components()->sync($syncData);

            DB::commit();

            return $item;
        } catch (\Exception $e) {
            Log::error("Menu item save/update failed: " . $e->getMessage());
            DB::rollBack();
            return null;
        }
    }

    public function getSyncDataForOutletStoreItems($request)
    {
        $syncData = [];
        $storeItems = $request->input('store_items', []);
        foreach ($storeItems as $outletStoreItemId => $values) {
            if (isset($values['checked']) && isset($values['quantity_used'])) {
                $syncData[$outletStoreItemId] = [
                    'quantity_used' => $values['quantity_used']
                ];
            }
        }
        return $syncData;
    }
}
