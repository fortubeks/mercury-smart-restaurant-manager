<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'restaurant_id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'phone_code',
        'phone',
        'other_phone',
        'birthday',
        'address',
        'state_id',
        'country_id',
    ];
    public function name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function relations()
    {
        return $this->belongsToMany(Customer::class, 'customer_relations', 'customer_id', 'related_customer_id')
            ->withPivot('relationship_type');
    }
    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}
