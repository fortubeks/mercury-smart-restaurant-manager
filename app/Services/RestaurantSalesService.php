<?php

namespace App\Services;

use App\Models\IncomingPayment;

class RestaurantSalesService
{
    protected $cash_sales = [];
    protected $transfer_sales = [];
    protected $pos_sales = [];
    protected $credit_sales = [];
    protected $wallet_sales = [];

    public function __construct()
    {
        // Initialize any dependencies here
    }

    public function getRestaurantSales($orders)
    {
        // Initialize sales array
        $restaurantSales = [
            'cash' => 0,
            'transfer' => 0,
            'pos' => 0,
            'credit' => 0,
            'wallet' => 0,
            'total' => 0
        ];

        // Query all payments related to these orders in a single query
        // Iterate over each order
        foreach ($orders as $order) {
            // Query payments associated with the BarOrders
            $payments = IncomingPayment::where('payable_id', $order->id)
                ->where('payable_type', 'App\Models\Order')->get();

            // Iterate over each payment and categorize
            foreach ($payments as $payment) {
                switch ($payment->payment_method) {
                    case 'cash':
                        $restaurantSales['cash'] += $payment->amount;
                        $this->cash_sales[] = $order;
                        break;
                    case 'transfer':
                        $restaurantSales['transfer'] += $payment->amount;
                        $this->transfer_sales[] = $order;
                        break;
                    case 'pos':
                        $restaurantSales['pos'] += $payment->amount;
                        $this->pos_sales[] = $order;
                        break;
                    case 'credit':
                        $restaurantSales['credit'] += $payment->amount;
                        $this->credit_sales[] = $order;
                        break;
                    case 'wallet':
                        $restaurantSales['wallet'] += $payment->amount;
                        $this->wallet_sales[] = $order;
                        break;
                }
            }
        }

        // Calculate total sales amount
        $restaurantSales['total'] = array_sum(array_slice($restaurantSales, 0, 5)); // Sum all methods except 'total'

        return $restaurantSales;
    }
}
