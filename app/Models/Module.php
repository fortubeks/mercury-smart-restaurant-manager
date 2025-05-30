<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class)->withTimestamps();
    }
}
