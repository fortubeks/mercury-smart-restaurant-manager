<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutletPreparedMenuItem extends Model
{
    protected $fillable = [
        'outlet_id',
        'menu_item_id',
        'qty',
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
