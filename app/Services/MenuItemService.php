<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuItemService
{
    protected $mediaUploadService;

    public function __construct(MediaUploadService $_mediaUploadService)
    {
        $this->mediaUploadService = $_mediaUploadService;
    }

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

            $requestData['is_available'] = $request->has('is_available') ? 1 : 0;
            $requestData['is_combo'] = $request->has('is_combo') ? 1 : 0;

            $categoryId = $request->menu_category_id ??
                MenuCategory::where('outlet_id', $request->outlet_id)->where('is_default', true)->value('id');

            $request->merge(['menu_category_id' => $categoryId]);

            $data = array_merge($request->except('image'), $requestData);

            if ($item) {
                $item->update($data);
            } else {
                $item = MenuItem::create($data);
            }

            // Sync ingredients
            $syncData = $this->getSyncDataForIngredients($request);
            $item->ingredients()->sync($syncData);
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

            if ($request->hasFile('image')) {
                $uploadedImagePath = $this->mediaUploadService->upload($request->file('image'), 'menu-items/images', 'image');
                $item->images()->create([
                    'image_path' => $uploadedImagePath,
                    'is_featured' => true
                ]);
            }

            DB::commit();

            return $item;
        } catch (\Exception $e) {
            Log::error("Menu item save/update failed: " . $e->getMessage());
            DB::rollBack();
            return null;
        }
    }

    public function getSyncDataForIngredients($request)
    {
        $syncData = [];
        $storeItems = $request->input('store_items', []);

        foreach ($storeItems as $ingredientId => $values) {
            if (isset($values['checked']) && isset($values['quantity_needed'])) {
                $syncData[$ingredientId] = ['quantity_needed' => $values['quantity_needed']];
            }
        }
        return $syncData;
    }
}
