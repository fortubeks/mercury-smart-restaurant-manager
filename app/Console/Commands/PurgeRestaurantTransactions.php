<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeRestaurantTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan purge:restaurant {restaurant_id}
     */
    protected $signature = 'purge:restaurant {restaurant_id} {--force : Actually delete data instead of dry-run}';

    protected $description = 'Purge all data (transactions, stores, inventory, orders, customers, etc.) for a specific restaurant.';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $restaurantId = $this->argument('restaurant_id');
        $force = $this->option('force');

        if (!$force) {
            $this->warn("⚠️ This will DELETE all data for restaurant_id={$restaurantId}.");
            $this->warn("Run with --force to actually delete.");
            return;
        }

        DB::transaction(function () use ($restaurantId) {

            // 💳 Customer Wallet Transactions
            DB::table('customer_wallet_transactions')->whereIn('customer_wallet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('customer_wallets')->whereIn('customer_id', function ($q2) use ($restaurantId) {
                    $q2->select('id')->from('customers')->where('restaurant_id', $restaurantId);
                });
            })->delete();

            // 💳 Customer Wallets
            // DB::table('customer_wallets')->whereIn('customer_id', function ($q) use ($restaurantId) {
            //     $q->select('id')->from('customers')->where('restaurant_id', $restaurantId);
            // })->delete();

            // 🧾 Incoming Payments
            DB::table('incoming_payments')->where('restaurant_id', $restaurantId)->delete();

            // 🧾 Outgoing Payments
            DB::table('outgoing_payments')->where('restaurant_id', $restaurantId)->delete();

            // 🏦 Bank Transactions
            DB::table('bank_account_transactions')->whereIn('bank_account_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('bank_accounts')->where('restaurant_id', $restaurantId);
            })->delete();

            // 🏦 Bank Accounts
            //DB::table('bank_accounts')->where('restaurant_id', $restaurantId)->delete();

            // 💸 Expenses & Details
            DB::table('expense_expense_items')->where('restaurant_id', $restaurantId)->delete();
            DB::table('expense_items')->where('restaurant_id', $restaurantId)->delete();
            //DB::table('expense_categories')->where('restaurant_id', $restaurantId)->delete();
            DB::table('expenses')->where('restaurant_id', $restaurantId)->delete();

            // 📦 Purchases
            DB::table('purchase_store_items')->whereIn('purchase_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('purchases')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('purchases')->where('restaurant_id', $restaurantId)->delete();
            //DB::table('purchase_categories')->where('restaurant_id', $restaurantId)->delete();

            // 🛒 Stores, Items, Stock
            DB::table('store_item_activities')->whereIn('store_item_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('store_items')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('store_issue_store_items')->whereIn('store_issue_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('store_issues')->whereIn('store_id', function ($q2) use ($restaurantId) {
                    $q2->select('id')->from('stores')->where('restaurant_id', $restaurantId);
                });
            })->delete();
            DB::table('store_issues')->whereIn('store_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('stores')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('store_stock_batches')->whereIn('store_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('stores')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('store_store_items')->whereIn('store_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('stores')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('store_items')->where('restaurant_id', $restaurantId)->delete();
            DB::table('store_item_categories')->where('restaurant_id', $restaurantId)->delete();
            //DB::table('stores')->where('restaurant_id', $restaurantId)->delete();

            // 📑 Orders
            DB::table('menu_item_order_ingredients')->whereIn('menu_item_order_id', function ($q) {
                $q->select('id')->from('menu_item_orders');
            })->delete();
            DB::table('menu_item_orders')->whereIn('order_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('orders')->whereIn('outlet_id', function ($q2) use ($restaurantId) {
                    $q2->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
                });
            })->delete();
            DB::table('orders')->whereIn('outlet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
            })->delete();

            // 🍽 Menus
            DB::table('menu_item_outlet_store_items')->whereIn('menu_item_id', function ($q) {
                $q->select('id')->from('menu_items');
            })->delete();
            DB::table('menu_item_images')->whereIn('menu_item_id', function ($q) {
                $q->select('id')->from('menu_items');
            })->delete();
            DB::table('menu_items')->whereIn('outlet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('menu_categories')->whereIn('outlet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('menus')->whereIn('outlet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
            })->delete();

            // 🏪 Outlets
            DB::table('outlet_store_items')->whereIn('outlet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('outlet_stock_batches')->whereIn('outlet_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('outlets')->where('restaurant_id', $restaurantId);
            })->delete();
            //DB::table('outlets')->where('restaurant_id', $restaurantId)->delete();

            // 👥 Customers
            // DB::table('customer_relations')->whereIn('customer_id', function ($q) use ($restaurantId) {
            //     $q->select('id')->from('customers')->where('restaurant_id', $restaurantId);
            // })->delete();
            // DB::table('customers')->where('restaurant_id', $restaurantId)->delete();

            // // 📦 Suppliers
            // DB::table('suppliers')->where('restaurant_id', $restaurantId)->delete();

            // // 🚴 Riders
            // DB::table('delivery_riders')->where('restaurant_id', $restaurantId)->delete();

            // 📊 Sales & Settlements
            DB::table('daily_sale_outlets')->whereIn('daily_sale_id', function ($q) use ($restaurantId) {
                $q->select('id')->from('daily_sales')->where('restaurant_id', $restaurantId);
            })->delete();
            DB::table('daily_sales')->where('restaurant_id', $restaurantId)->delete();
            DB::table('settlements')->where('restaurant_id', $restaurantId)->delete();

            // ⚙️ Settings, Subscriptions, Modules
            // DB::table('subscriptions')->where('restaurant_id', $restaurantId)->delete();
            // DB::table('app_settings')->where('restaurant_id', $restaurantId)->delete();
            // DB::table('module_restaurant')->where('restaurant_id', $restaurantId)->delete();
            // DB::table('taxes')->where('restaurant_id', $restaurantId)->delete();

            // 👤 Users (staff of the restaurant)
            //DB::table('users')->where('restaurant_id', $restaurantId)->delete();

            // 🏠 Finally delete the Restaurant itself
            //DB::table('restaurants')->where('id', $restaurantId)->delete();
        });
    }
}
