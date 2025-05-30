<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\RestaurantSetupService;
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
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'email@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08090839412',
            'role_id' => 1,
            'is_active' => true,
            'user_id' => 1,
            'outlet_id' => 1,
            'last_login' => now(),
        ]);

        if ($user->is_super_admin) {
            // No need for another transaction here
            $restaurant = (new RestaurantSetupService)->createWithDefaults([
                'user_id' => $user->id,
                'name' => 'Main Restaurant',
            ]);

            $user->update([
                'restaurant_id' => $restaurant->id,
            ]);
        }

        // Assign roles if any
        if (method_exists($user, 'getRoleIds') && $user->getRoleIds()) {
            $user->roles()->sync($user->getRoleIds());
        }
    }
}
