<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySaleOutlet extends Model
{
    protected $fillable = [
        'id',
        'daily_sale_id',
        'outlet_id',
        'total',
        'cash',
        'pos',
        'transfer',
        'wallet',
        'credit',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function dailySale()
    {
        return $this->belongsTo(DailySale::class, 'daily_sale_id');
    }
}
