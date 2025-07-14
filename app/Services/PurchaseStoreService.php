<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseStoreItem;
use App\Models\StoreItem;
use App\Models\StoreItemActivity;
use App\Models\StoreStockBatch;
use Illuminate\Support\Facades\DB;

class PurchaseStoreService
{
    /**
     * Create a purchase, log item movements, and create FIFO-ready store stock batches.
     */
    public function create(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            $purchase = Purchase::create([
                'store_id' => $data['store_id'],
                'restaurant_id' => $data['restaurant_id'],
                'purchase_date' => $data['purchase_date'],
                'supplier_id' => $data['supplier_id'] ?? null,
                'sub_total' => $data['sub_total'],
                'total_amount' => $data['total_amount'],
                'status' => $data['status'] ?? 'received',
                'note' => $data['note'] ?? null,
                'item_category_id' => $data['category_id'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $storeItem = StoreItem::findOrFail($item['store_item_id']);

                $previousQty = $storeItem->getQtyForStore($data['store_id']);
                $currentQty = $previousQty;
                // Then sync the pivot table
                $storeItem->stores()->syncWithoutDetaching([
                    $data['store_id'] => [
                        'qty' => $item['qty'],
                        'unit_cost' => $item['rate'],
                        'batch_reference' => $item['batch_reference'] ?? null,
                        'expiry_date' => $item['expiry_date'] ?? null,
                    ]
                ]);

                // Log purchase item
                $purchaseStoreItem = PurchaseStoreItem::create([
                    'purchase_id' => $purchase->id,
                    'store_item_id' => $item['store_item_id'],
                    'store_id' => $data['store_id'],
                    'qty' => $item['qty'],
                    'received' => $item['received'],
                    'rate' => $item['rate'],
                    'sub_total' => $item['sub_total'],
                    'total_amount' => $item['total_amount'],
                    'unit_qty' => $item['unit_qty'],
                ]);

                // Log store item activity
                StoreItemActivity::create([
                    'purchase_id' => $purchase->id,
                    'store_item_id' => $item['store_item_id'],
                    'store_id' => $data['store_id'],
                    'qty' => $item['qty'],
                    'previous_qty' => $previousQty,
                    'activity_date' => $data['purchase_date'],
                    'current_qty' => $currentQty,
                    'description' => $item['description'] ?? 'Purchase',
                ]);

                // Create FIFO-compatible store batch
                StoreStockBatch::create([
                    'store_id' => $data['store_id'],
                    'store_item_id' => $item['store_item_id'],
                    'purchase_store_item_id' => $purchaseStoreItem->id,
                    'quantity_received' => $item['unit_qty'],
                    'quantity_remaining' => $item['unit_qty'],
                    'unit_cost' => $item['rate'],
                    'batch_reference' => $item['batch_reference'] ?? null,
                    'expiry_date' => $item['expiry_date'] ?? null,
                ]);
            }

            return $purchase;
        });
    }
    // public function create(array $data): Purchase
    // {
    //     return DB::transaction(function () use ($data) {
    //         $purchase = Purchase::create([
    //             'store_id' => $data['store_id'],
    //             'restaurant_id' => $data['restaurant_id'],
    //             'purchase_date' => $data['purchase_date'],
    //             'supplier_id' => $data['supplier_id'] ?? null,
    //             'sub_total' => $data['sub_total'],
    //             'total_amount' => $data['total_amount'],
    //             'status' => $data['status'] ?? 'received',
    //             'note' => $data['note'] ?? null,
    //             'item_category_id' => $data['category_id'] ?? null,
    //         ]);

    //         foreach ($data['items'] as $item) {
    //             $storeItem = StoreItem::findOrFail($item['store_item_id']);

    //             $previous_qty = $storeItem->qty;
    //             $storeItem->qty += $item['unit_qty'];
    //             $storeItem->save();

    //             PurchaseStoreItem::create([
    //                 'purchase_id' => $purchase->id,
    //                 'store_item_id' => $item['store_item_id'],
    //                 'store_id' => $data['store_id'],
    //                 'qty' => $item['qty'],
    //                 'received' => $item['received'],
    //                 'rate' => $item['rate'],
    //                 'sub_total' => $item['sub_total'],
    //                 'total_amount' => $item['total_amount'],
    //                 'unit_qty' => $item['unit_qty'],
    //             ]);

    //             StoreItemActivity::create([
    //                 'purchase_id' => $purchase->id,
    //                 'store_item_id' => $item['store_item_id'],
    //                 'store_id' => $data['store_id'],
    //                 'qty' => $item['qty'],
    //                 'previous_qty' => $previous_qty,
    //                 'activity_date' => $data['purchase_date'],
    //                 'current_qty' => $storeItem->qty,
    //                 'description' => $item['description'] ?? 'Purchase',
    //             ]);
    //         }

    //         return $purchase;
    //     });
    // }
}
