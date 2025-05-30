<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'is_default', 'is_sales_outlet'];
}
