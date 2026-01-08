<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItemOrder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'menu_item_id',
        'order_id',
        'qty',
        'sub_total',
        'tax_rate',
        'tax_amount',
        'discount_rate',
        'discount_type',
        'discount_amount',
        'total_amount'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
