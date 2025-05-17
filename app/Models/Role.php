<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const SUPER_ADMIN = 'Super Admin';
    const MANAGER = 'Manager';
    const CASHIER = 'Cashier';
    const WAITER = 'Waiter';
    const CHEF = 'Chef';
    protected $fillable = ['name'];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
