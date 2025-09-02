<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\BankAccount;
use App\Models\ExpenseCategory;
use App\Models\Outlet;
use App\Models\PurchaseCategory;
use App\Models\Restaurant;
use App\Models\Store;
use App\Models\StoreItemCategory;
use Illuminate\Support\Facades\DB;

class RestaurantSetupService
{
    public function createWithDefaults(array $data)
    {
        return DB::transaction(function () use ($data) {
            $restaurant = Restaurant::create($data);

            AppSetting::create([
                'restaurant_id' => $restaurant->id,
            ]);

            Outlet::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Main Restaurant Outlet',
                'is_default' => true,
                'is_sales_outlet' => true,
            ]);

            //create default cash bank account for the restaurant
            BankAccount::create([
                'restaurant_id' => $restaurant->id,
                'account_name' => 'Default Cash Account',
                'account_number' => '0000000000',
                'balance' => 0.00,
            ]);

            // Create default expense categories
            ExpenseCategory::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Default',
                'is_default' => true,
            ]);

            // Create default expense categories
            PurchaseCategory::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Default',
                'is_default' => true,
            ]);

            // create default store
            Store::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Main Store',
                'is_default' => true,
            ]);

            // Create default store item categories
            $defaultCategories = ['Food', 'Drinks', 'Ingredients', 'Packaging', 'Others', 'Administrative', 'Housekeeping', 'Staff'];
            foreach ($defaultCategories as $name) {
                StoreItemCategory::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => $name,
                    'is_default' => true,
                ]);
            }

            return $restaurant;
        });
    }
}
