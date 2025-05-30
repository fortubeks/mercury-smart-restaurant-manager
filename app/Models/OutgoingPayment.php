<?php

namespace App\Models;

use App\Services\BankAccountService;
use Illuminate\Database\Eloquent\Model;

class OutgoingPayment extends Model
{
    protected $fillable = [
        'amount',
        'date_of_payment',
        'bank_account_id',
        'restaurant_id',
        'supplier_id',
        'payable_id',
        'payable_type',
        'note',
        'mode_of_payment',
    ];

    protected $appends = ['formatted_date_of_payment'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($outgoingPayment) {
            //delete the bank account transaction
            $outgoingPayment->transaction()->delete();
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function getFormattedDateOfPaymentAttribute()
    {
        return $this->date_of_payment->format('d M, Y');
    }
    public function expenses()
    {
        return $this->belongsToMany(Expense::class, 'expense_outgoing_payment')
            ->withTimestamps();
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'outgoing_payment_purchase')
            ->withTimestamps();
    }
}
