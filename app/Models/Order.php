<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function ($order) {
            $order->reference = 'ORD-' . now()->format('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
            $order->save();
        });
    }
}
