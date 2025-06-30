<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreStockBatch extends Model
{
    protected $fillable = [
        'store_id',
        'store_item_id',
        'purchase_store_item_id',
        'quantity_received',
        'quantity_remaining',
        'unit_cost',
        'batch_reference',
        'expiry_date'
    ];
}
