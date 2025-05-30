<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['name', 'rate', 'restaurant_id', 'is_active'];
}
