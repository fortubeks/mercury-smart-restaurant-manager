<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletStoreItem extends Model
{
    protected $fillable = ['store_item_id', 'outlet_id', 'qty', 'price'];

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_outlet_store_items');
    }

    public function storeItem()
    {
        return $this->belongsTo(StoreItem::class);
    }
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
    // public function restaurantItems()
    // {
    //     return $this->belongsToMany(RestaurantItem::class, 'restaurant_item_outlet_store_item')
    //         ->withPivot('quantity_used')
    //         ->withTimestamps();
    // }
}
