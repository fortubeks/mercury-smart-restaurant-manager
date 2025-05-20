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
        'country_id',
        'state_id',
        'restaurant_type'
    ];
    public function getIsRestaurantProfileCompleteAttribute()
    {
        return $this->name && $this->address && $this->phone && $this->country_id && $this->state_id;
    }

    public function appSetting()
    {
        return $this->hasOne(AppSetting::class);
    }
}
