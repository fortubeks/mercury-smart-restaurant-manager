<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    protected $fillable = [
        'id',
        'restaurant_id',
        'outlet_id',
        'shift_date',
        'shift',
        'total',
        'cash',
        'pos',
        'transfer',
        'wallet',
        'credit',
        'total',
        'discounts',
        'tax',
        'opening_balance',
        'closing_balance',
        'expected_cash_balance',
        'cash_outflow',
        'audited_by'
    ];
    public function outletSales()
    {
        return $this->hasMany(DailySaleOutlet::class, 'daily_sale_id');
    }
}
