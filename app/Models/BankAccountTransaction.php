<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccountTransaction extends Model
{
    protected $fillable = [
        'hotel_id',
        'bank_account_id',
        'transaction_type',
        'amount',
        'description',
        'mode_of_payment',
        'transaction_date',
        'transactionable_type',
        'transactionable_id',
    ];
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
    public function transactionable()
    {
        //record of incoming or outgoing payment
        return $this->morphTo();
    }
}
