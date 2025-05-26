<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['restaurant_id', 'name'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($outlet) {
            //set user outlet to this created outlet
            auth()->user()->update(['outlet_id' => $outlet->id]);
        });
    }
}
