<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = ['bank_name', 'account_name', 'account_number', 'restaurant_id', 'balance'];

    protected static function boot()
    {
        parent::boot();

        // Prevent changes to account_name and account_number for the default cash account
        static::updating(function ($model) {
            $original = $model->getOriginal();

            if ($model->isDefaultCashAccount()) {
                if ($model->account_name !== $original['account_name'] || $model->account_number !== $original['account_number']) {
                    return false; // Prevent update
                }
            }
        });
        //prevent delete if bank account has transactions
        static::deleting(function ($model) {
            if ($model->transactions()->exists()) {
                return false; // Prevent delete
            }
        });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankAccountTransaction::class);
    }

    public function defaultCashAccount()
    {
        return self::where('account_name', 'Cash Account')
            ->where('account_number', '0000000000')
            ->where('restaurant_id', restaurantId())
            ->first();
    }

    public function isDefaultCashAccount()
    {
        return $this->account_name === 'Cash Account' && $this->account_number === '0000000000';
    }
}
