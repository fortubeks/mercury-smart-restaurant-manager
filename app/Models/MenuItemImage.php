<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemImage extends Model
{
    protected $fillable = ['menu_item_id', 'image_path', 'is_featured'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
