<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMigrationDetail extends Model
{
    protected $fillable = [
        'item_migration_id',
        'store_item_id',
        'qty',
        'from_balance',
        'to_balance',
    ];

    public function itemMigration()
    {
        return $this->belongsTo(ItemMigration::class);
    }

    public function storeItem()
    {
        return $this->belongsTo(StoreItem::class);
    }
}
