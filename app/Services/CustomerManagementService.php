<?php

namespace App\Services;


class CustomerManagementService
{
    public function getCustomerMetrics($customer)
    {
        $orders = $customer->orders()->orderBy('created_at')->get();

        if ($orders->isEmpty()) {
            return [
                'first_visit' => null,
                'last_visit' => null,
                'total_spend' => 0,
                'total_visits' => 0,
            ];
        }

        return [
            'first_visit' => $orders->first()->order_date,
            'last_visit' => $orders->last()->order_date,
            'total_spend' => $orders->sum('total_amount'),
            'total_visits' => $orders->count(),
        ];
    }
}
