<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerWalletTransaction extends Model
{
    protected $fillable = ['customer_wallet_id', 'restaurant_id', 'transaction_type', 'amount', 'description', 'payment_id', 'payment_method', 'balance', 'transaction_date'];

    public function customerWallet()
    {
        return $this->belongsTo(CustomerWallet::class);
    }

    public function payment()
    {
        return $this->belongsTo(IncomingPayment::class);
    }

    public function payments()
    {
        return $this->morphMany(IncomingPayment::class, 'payable');
    }

    public function settlements()
    {
        return $this->hasManyThrough(IncomingPayment::class, Settlement::class, 'payable_id', 'payable_id')
            ->where('settlements.payable_type', CustomerWalletTransaction::class)
            ->where('incoming_payments.payable_type', Settlement::class);
    }
}
