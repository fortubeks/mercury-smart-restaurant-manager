<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)->withTimestamps();
    }

    public function defaultCashBankAccount()
    {
        //try to get the default cash bank account
        $bankAccount = $this->bankAccounts()->where('account_name', 'like', '%cash%')->where('account_number', '0000000000')->first();

        return $bankAccount;
    }

    public function defaultStore()
    {
        return $this->hasOne(Store::class);
        //return $this->hasOne(Store::class)->where('is_default', true);
    }

    public function outlets()
    {
        return $this->hasMany(Outlet::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function deliveryRiders()
    {
        return $this->hasMany(DeliveryRider::class);
    }

    public function dailySales()
    {
        return $this->hasMany(DailySale::class);
    }
}
