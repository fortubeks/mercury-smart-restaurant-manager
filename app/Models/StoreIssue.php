<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIssue extends Model
{
    protected $fillable = ['recipient_name', 'note', 'user_id', 'store_id', 'type', 'outlet_id'];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function storeIssueStoreItems()
    {
        return $this->hasMany(StoreIssueStoreItem::class);
    }
}
