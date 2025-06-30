<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'is_default', 'is_sales_outlet'];

    public function storeItems()
    {
        return $this->belongsToMany(StoreItem::class, 'store_store_items')
            ->withPivot('id', 'qty', 'unit_cost', 'batch_number', 'expiry_date')
            ->withTimestamps();
    }
}
