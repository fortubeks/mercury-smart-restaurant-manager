<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'menu_category_id',
        'image',
        'is_available',
        'outlet_id'
    ];

    protected $quantity = 100;
    protected $appends = ['quantity'];

    public function getQuantityAttribute()
    {
        // Return 100 if inventory management is disabled
        if (!restaurant()->appSetting->manage_stock) {
            return 100;
        }

        // If the item is directly linked to one store item (simple item)
        if ($this->outletStoreItem && $this->outletStoreItems->isEmpty()) {
            return floor($this->outletStoreItem->qty ?? 0);
        }

        // if the item is a combo item
        if ($this->is_combo) {
            $portion_counts = [];

            foreach ($this->components as $component) {
                $stock_qty = $component->outletStoreItem->qty;
                $qty_needed = $component->pivot->quantity;

                if ($qty_needed > 0) {
                    $portion_counts[] = floor($stock_qty / $qty_needed);
                }
            }

            return count($portion_counts) ? min($portion_counts) : 0;
        }

        // If the item has multiple linked store items
        $portion_counts = [];

        foreach ($this->outletStoreItems as $outletStoreItem) {
            $stock_qty = $outletStoreItem->qty;
            $qty_needed = $outletStoreItem->pivot->quantity_used;

            if ($qty_needed > 0) {
                $portion_counts[] = floor($stock_qty / $qty_needed);
            }
        }

        return count($portion_counts) ? min($portion_counts) : 0;
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    // Items that make up this combo
    // Usage: $combo = MenuItem::find(10);
    // foreach ($combo->items as $item) {
    //     echo $item->name . " x " . $item->pivot->qty;
    // }
    public function components()
    {
        return $this->belongsToMany(MenuItem::class, 'combo_menu_item_menu_item', 'combo_id', 'menu_item_id')->withPivot('qty');
    }

    // If you want to find which combos this item belongs to
    public function combos()
    {
        return $this->belongsToMany(MenuItem::class, 'combo_menu_item_menu_item', 'menu_item_id', 'combo_id')->withPivot('qty');
    }

    public function outletStoreItems()
    {
        return $this->belongsToMany(OutletStoreItem::class, 'menu_item_outlet_store_items')
            ->withPivot('quantity_used')
            ->withTimestamps();
    }

    public function menuItemOrders()
    {
        return $this->hasMany(MenuItemOrder::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'menu_item_orders')
            ->withPivot(['qty', 'amount', 'tax_rate', 'tax_amount', 'discount_rate', 'discount_amount', 'total_amount'])
            ->withTimestamps();
    }
}
