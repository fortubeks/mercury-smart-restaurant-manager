<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreItemActivity extends Model
{
    protected $fillable = [
        'store_item_id',
        'qty',
        'store_id',
        'purchase_id',
        'store_issue_id',
        'previous_qty',
        'activity_date',
        'current_qty',
        'description',
    ];

    public function storeItem()
    {
        return $this->belongsTo(StoreItem::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function storeIssue()
    {
        return $this->belongsTo(StoreIssue::class);
    }
}
