<?php

namespace App\Services;

use App\Models\StoreStockBatch;
use App\Models\OutletStockBatch;
use App\Models\MenuItemOrder;
use App\Models\MenuItemOrderIngredient;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    /**
     * Receive stock into a store (via purchase)
     */
    public function receiveIntoStore($storeId, $storeItemId, $quantity, $unitCost, $batchRef = null, $expiryDate = null)
    {
        return StoreStockBatch::create([
            'store_id' => $storeId,
            'store_item_id' => $storeItemId,
            'quantity_received' => $quantity,
            'quantity_remaining' => $quantity,
            'unit_cost' => $unitCost,
            'batch_reference' => $batchRef,
            'expiry_date' => $expiryDate,
        ]);
    }

    /**
     * Transfer stock from store to outlet using FIFO batches
     */
    public function transferToOutlet($storeId, $storeItemId, $outletId, $quantity)
    {
        $batches = StoreStockBatch::where('store_id', $storeId)
            ->where('store_item_id', $storeItemId)
            ->where('quantity_remaining', '>', 0)
            ->orderBy('created_at')
            ->get();

        $remaining = $quantity;

        foreach ($batches as $batch) {
            if ($remaining <= 0) break;

            $take = min($remaining, $batch->quantity_remaining);

            OutletStockBatch::create([
                'outlet_id' => $outletId,
                'store_item_id' => $storeItemId,
                'store_stock_batch_id' => $batch->id,
                'quantity_received' => $take,
                'quantity_remaining' => $take,
                'unit_cost' => $batch->unit_cost,
            ]);

            $batch->decrement('quantity_remaining', $take);
            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new \Exception('Insufficient stock in store for transfer.');
        }
    }

    /**
     * Deduct stock from outlet for a menu item order using FIFO logic
     */
    public function consumeForMenuItemOrder(MenuItemOrder $order)
    {
        $menuItem = $order->menuItem;
        $outlet = $order->outlet;

        foreach ($menuItem->outletStoreItems as $ingredient) {
            $requiredQty = $ingredient->pivot->quantity_used;
            $storeItemId = $ingredient->store_item_id;

            $batches = OutletStockBatch::where('outlet_id', $outlet->id)
                ->where('store_item_id', $storeItemId)
                ->where('quantity_remaining', '>', 0)
                ->orderBy('created_at')
                ->get();

            $remaining = $requiredQty;

            foreach ($batches as $batch) {
                if ($remaining <= 0) break;

                $take = min($remaining, $batch->quantity_remaining);

                MenuItemOrderIngredient::create([
                    'menu_item_order_id' => $order->id,
                    'outlet_stock_batch_id' => $batch->id,
                    'store_item_id' => $storeItemId,
                    'quantity_used' => $take,
                    'unit_cost' => $batch->unit_cost,
                    'total_cost' => $take * $batch->unit_cost,
                ]);

                $batch->decrement('quantity_remaining', $take);
                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new \Exception("Insufficient stock in outlet for item: {$storeItemId}");
            }
        }
    }
}
