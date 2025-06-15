<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseExpenseItem extends Model
{
    protected $fillable = [
        'expense_id',
        'expense_item_id',
        'restaurant_id',
        'qty',
        'rate',
        'amount',
        'unit_qty'
    ];
    public function expenseItem()
    {
        return $this->belongsTo(ExpenseItem::class, 'expense_item_id');
    }
}
