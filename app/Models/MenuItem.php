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
        'outlet_id',
        'is_combo',
    ];

    protected $quantity = 100;
    protected $appends = ['quantity'];

    public function getQuantityAttribute()
    {
        return $this->calculateQuantity();
    }

    public function calculateQuantity($forceCheck = false)
    {
        // Return 100 if inventory management is disabled (only for top-level calls)
        if (!restaurant()->appSetting->manage_stock && !$forceCheck) {
            return 100;
        }

        // Case 1: Combo item (combination of other menu items)
        if ($this->is_combo) {
            $portion_counts = [];

            foreach ($this->components as $component) {
                // recursive call, but force ingredient check
                $stock_qty = $component->calculateQuantity(true);
                $qty_needed = $component->pivot->qty;

                if ($qty_needed > 0) {
                    $portion_counts[] = floor($stock_qty / $qty_needed);
                }
            }

            return count($portion_counts) ? min($portion_counts) : 0;
        }

        // Case 2: Normal menu item (linked to ingredients)
        $portion_counts = [];

        foreach ($this->ingredients as $ingredient) {
            $outletStoreItem = $ingredient->outletStoreItem ?? null;

            if ($outletStoreItem && $ingredient->pivot) {
                $stock_qty = $outletStoreItem->qty;
                $qty_needed = $ingredient->pivot->quantity_needed;

                if ($qty_needed > 0) {
                    $portion_counts[] = floor($stock_qty / $qty_needed);
                }
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

    // public function outletStoreItems()
    // {
    //     return $this->belongsToMany(OutletStoreItem::class, 'menu_item_outlet_store_items')
    //         ->withPivot('quantity_used')
    //         ->withTimestamps();
    // }

    public function ingredients()
    {
        return $this->belongsToMany(StoreItem::class, 'menu_item_ingredients')
            ->withPivot('quantity_needed');
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

    public function images()
    {
        return $this->hasMany(MenuItemImage::class);
    }

    public function featuredImage()
    {
        return $this->hasOne(MenuItemImage::class)->where('is_featured', true);
    }
}
