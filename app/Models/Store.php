<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'restaurant_id',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function storeItems()
    {
        return $this->belongsToMany(StoreItem::class, 'store_store_items')
            ->withPivot('id', 'qty', 'unit_cost', 'batch_number', 'expiry_date')
            ->withTimestamps();
    }
}
