<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use Illuminate\Http\Request;

class DailySaleController extends Controller
{
    public function index(DailySale $model)
    {
        return theme_view('daily-sales.index', [
            'sales' => $model->where('restaurant_id', restaurantId())->latest()->get()
        ]);
    }

    public function create(DailySale $daily_sale)
    {
        $this->authorize('create', $daily_sale);

        $restaurant = restaurant();
        $latestRecord = DailySale::where('restaurant_id', $restaurant->id)->latest()->first();
        //if there is no record, set the current audit date to today
        $latestRecordShift = $latestRecord ? $latestRecord->shift_date : now()->toDateString();

        $current_audit_date = auth()->user()->current_shift;


        if ($latestRecord && $latestRecord->exists()) {
            $last_audited_date = $latestRecord->shift_date;
            // Add 1 day to the last audited date to get the current day audit date
            $current_audit_date = Carbon::createFromFormat('Y-m-d', $last_audited_date)->addDay()->toDateString();
        }

        //get the defaultOutlets
        $defaultBarId = $restaurant->defaultBar()->id;
        $defaultRestaurantId = $restaurant->defaultRestaurant()->id;

        //get all sales records
        $restaurant_sales = $restaurant->restaurantOrders()->with('payments', 'guest')
            ->where('order_date', $current_audit_date)
            ->where('outlet_id', $defaultRestaurantId)->get();

        $wallet_deposits = $restaurant->guestWalletTransactions()->with('guestWallet.guest')
            ->where('transaction_date', $current_audit_date)->where('transaction_type', 'credit')->get();

        $cashAccount = $restaurant->defaultCashBankAccount();
        $cashAccountBalance = $cashAccount->balance;

        //get the most recent DailySales record and get the cash_account_balance
        $previousDayCashAtHand = $restaurant->dailySales()->latest()->first() ?
            $restaurant->dailySales()->latest()->first()->cash_account_balance : 0;

        //get the total of all the payments made to the restaurant
        //Extract payment methods from sales record

        $restaurantSales = $this->getRestaurantSales($restaurant_sales);
    }
}
