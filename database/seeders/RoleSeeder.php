<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin'],
            ['name' => 'Manager'],
            ['name' => 'Accountant'],
            ['name' => 'Cashier'],
            ['name' => 'Chef'],
            ['name' => 'Waiter'],
            ['name' => 'Host'],
            ['name' => 'Store Keeper'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
