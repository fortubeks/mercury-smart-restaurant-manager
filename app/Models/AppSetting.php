<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['restaurant_id', 'manage_stock', 'kitchen_store', 'include_tax'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
