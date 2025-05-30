<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseStoreItem;
use App\Models\StoreItem;
use App\Models\StoreItemActivity;
use Illuminate\Support\Facades\DB;

class PurchaseStoreService
{
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

                $previous_qty = $storeItem->qty;
                $storeItem->qty += $item['unit_qty'];
                $storeItem->save();

                PurchaseStoreItem::create([
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

                StoreItemActivity::create([
                    'purchase_id' => $purchase->id,
                    'store_item_id' => $item['store_item_id'],
                    'store_id' => $data['store_id'],
                    'qty' => $item['qty'],
                    'previous_qty' => $previous_qty,
                    'activity_date' => $data['purchase_date'],
                    'current_qty' => $storeItem->qty,
                    'description' => $item['description'] ?? 'Purchase',
                ]);
            }

            return $purchase;
        });
    }
}
