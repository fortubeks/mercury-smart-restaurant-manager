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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($restaurant) {
            DB::transaction(function () use ($restaurant) {
                // Create a corresponding app_settings record for the restaurant
                AppSetting::create([
                    'restaurant_id' => $restaurant->id,
                    'manage_stock' => 0,
                    'kitchen_store' => 0,
                ]);

                // Create a default outlet under the restaurant
                Outlet::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => 'Main Outlet',
                ]);

                //create default cash bank account for the restaurant
                BankAccount::create([
                    'restaurant_id' => $restaurant->id,
                    'account_name' => 'Default Cash Account',
                    'account_number' => '0000000000',
                    'balance' => 0.00,
                ]);
            });
        });
    }

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

    public function defaultCashBankAccount()
    {
        //try to get the default cash bank account
        $bankAccount = $this->bankAccounts()->where('account_name', 'like', '%cash%')->where('account_number', '0000000000')->first();

        return $bankAccount;
    }
}
