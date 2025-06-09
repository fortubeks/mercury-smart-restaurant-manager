<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreItemCategory extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'is_default', 'parent_id'];
}
