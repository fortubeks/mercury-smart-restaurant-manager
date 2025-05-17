<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'user_id',
        'logo',
    ];
    public function getIsRestaurantProfileCompleteAttribute()
    {
        return $this->name;
    }
}
