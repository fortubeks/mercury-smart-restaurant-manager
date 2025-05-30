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
        return $this->belongsToMany(OutgoingPayment::class, 'expense_outgoing_payment')
            ->withTimestamps();
    }
}
