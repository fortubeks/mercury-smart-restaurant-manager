<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseStoreItem extends Model
{
    protected $fillable = [
        'purchase_id',
        'store_item_id',
        'qty',
        'rate',
        'sub_total',
        'unit_qty',
        'received',
        'discount',
        'tax_rate',
        'tax_amount',
        'total_amount'
    ];
    public function storeItem()
    {
        return $this->belongsTo(StoreItem::class, 'store_item_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
