<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = ['name', 'restaurant_id', 'is_default', 'parent_id'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
