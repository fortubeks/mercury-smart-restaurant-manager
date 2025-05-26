<?php

namespace App\Models;

use App\Services\BankAccountService;
use Illuminate\Database\Eloquent\Model;

class IncomingPayment extends Model
{
    protected $fillable = ['restaurant_id', 'amount', 'payment_method', 'payable_id', 'payable_type', 'bank_account_id', 'date_of_payment'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            // Increase the bank account balance
            if ($payment->bank_account_id) {
                $bankAccount = BankAccount::find($payment->bank_account_id);
                if ($bankAccount) {
                    $bankAccountService = new BankAccountService();
                    $bankAccountService->credit($payment);
                }
            }
        });
        static::deleting(function ($payment) {
            if ($payment->bank_account_id) {
                $bankAccount = BankAccount::find($payment->bank_account_id);
                if ($bankAccount) {
                    $bankAccount->decrement('balance', $payment->amount);
                }
            }
            // Delete related wallet transaction
            $payment->guestWalletTransaction()->delete();
            //delete the bank account transaction
            $payment->transaction()->delete();
        });
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->morphOne(BankAccountTransaction::class, 'transactionable');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function guestWalletTransaction()
    {
        return $this->hasOne(GuestWalletTransaction::class);
    }

    public function companyWalletTransaction()
    {
        return $this->hasOne(CompanyWalletTransaction::class);
    }
}
