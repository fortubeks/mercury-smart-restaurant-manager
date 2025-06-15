<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRider extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'phone',
        'vehicle_type',
        'vehicle_number',
        'status',
        'profile_picture',
        'notes',
        'emergency_contact',
        'emergency_contact_name',
    ];

    /**
     * Get the restaurant that owns the delivery rider.
     */
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_rider_id');
    }
}
