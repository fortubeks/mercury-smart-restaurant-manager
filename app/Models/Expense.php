<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['supplier_id', 'expense_category_id', 'description', 'amount', 'expense_date', 'note', 'restaurant_id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($expense) {
            foreach ($expense->outgoingPayments as $payment) {
                $payment->delete();
            }
        });
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function expenseCategory()
    {
        return $this->belongsTo('App\Models\ExpenseCategory');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
    }

    public function outgoingPayments()
    {
        return $this->morphMany(OutgoingPayment::class, 'payable');
    }

    public function items()
    {
        return $this->hasMany(ExpenseExpenseItem::class, 'expense_id');
    }

    public function getItems()
    {
        $itemsString = '';
        $itemNames = $this->items->map(function ($expenseItem) {
            return $expenseItem->expenseItem->name;
        })->toArray();

        $itemsString = implode(', ', $itemNames);
        return $itemsString;
    }
    public function paymentStatus()
    {
        $status = "Not Paid";
        if ($this->outgoingPayments()->sum('amount') >= $this->amount) {
            $status = "All Paid";
        }
        if ($this->outgoingPayments()->sum('amount') < $this->amount  && $this->outgoingPayments()->sum('amount') > 0) {
            $status = "Part Paid";
        }
        return $status;
    }
}
