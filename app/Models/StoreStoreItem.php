<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreStoreItem extends Model
{
    protected $fillable = [
        'qty',
        'unit_cost',
        'batch_reference',
        'expiry_date'
    ];
}
