<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreItem extends Model
{
    protected $fillable = [
        'name',
        'item_category_id',
        'description',
        'unit_measurement',
        'qty',
        'for_sale',
        'code',
        'low_stock_alert',
        'cost_price',
        'selling_price',
        'image',
        'restaurant_id',
    ];

    public function category()
    {
        return $this->belongsTo(StoreItemCategory::class, 'item_category_id');
    }


    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_store_items')
            ->withPivot('qty', 'unit_cost', 'batch_reference', 'expiry_date')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(StoreItemActivity::class);
    }

    public function outletStoreItem()
    {
        return $this->hasOne(OutletStoreItem::class);
    }

    public function outletStoreItems()
    {
        return $this->hasMany(OutletStoreItem::class);
    }

    public function menuItems()
    {
        return $this->hasManyThrough(
            MenuItem::class,
            OutletStoreItem::class,
            'store_item_id', // Foreign key on OutletStoreItem table
            'id',            // Foreign key on MenuItem table (we will filter via pivot)
            'id',            // Local key on StoreItem
            'id'             // Local key on OutletStoreItem
        )->whereHas('outletStoreItems', function ($q) {
            $q->whereHas('menuItems');
        });
    }

    public function getOpeningStock($startDate)
    {
        // Assuming you have a relationship to track stock movements,
        // for example, activities model with 'quantity' field.
        // You need to sum up all stock movements until the given start date.

        //opening stock is the sum of all activities before the start date
        //based on my database, this is the current_qty of the first activity

        $openingStock = $this->qty;
        //add the qty of the item if the item is found in outlet store item
        if ($this->outletStoreItem) {
            $openingStock += $this->outletStoreItems()->sum('qty');
        }
        // Check if there are any activities before the start date
        if ($this->activities()->whereDate('activity_date', '<', $startDate)->exists()) {

            // Get the first activity before the start date
            try {
                $openingStock = $this->activities()
                    ->whereDate('activity_date', '<', $startDate)
                    ->orderBy('activity_date', 'asc')
                    ->first()
                    ->qty;
            } catch (\Exception $e) {
                // Handle the case where there are no activities before the start date
                logger($this->id);
                $openingStock = 0;
            }
        }

        return $openingStock;
    }

    public function getQtyForStore($storeId)
    {
        return $this->stores
            ->firstWhere('pivot.store_id', $storeId)
            ->pivot->qty ?? 0;
    }

    //use this when you have loaded only one store
    public function getStoreQtyAttribute()
    {
        return $this->stores->first()->pivot->qty ?? 0;
    }

    public function getTotalQtyAttribute()
    {
        return $this->stores->sum(fn($store) => $store->pivot->qty ?? 0);
    }
}
