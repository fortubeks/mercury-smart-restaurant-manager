<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'email@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08090839412',
            'role_id' => 1,
            'is_active' => true,
            'user_id' => 1,
            'last_login' => now(),
        ]);
    }
}
