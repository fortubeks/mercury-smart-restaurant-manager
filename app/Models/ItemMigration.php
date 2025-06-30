<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMigration extends Model
{
    protected $fillable = [
        'note',
        'from_id',
        'from_type',
        'to_id',
        'to_type',
        'user_id',
        'restaurant_id'
    ];

    public function from()
    {
        return $this->morphTo();
    }

    public function to()
    {
        return $this->morphTo();
    }

    public function details()
    {
        return $this->hasMany(ItemMigrationDetail::class);
    }
}
