<?php

namespace App\Services;

use App\Models\IncomingPayment;
use App\Models\Order;

class DailySaleComputationService
{
    /**
     * Compute daily sales for the given date and outlets.
     *
     * @param \Carbon\Carbon $date
     * @param array $outletIds
     * @return array
     */
    public function computeSales($current_audit_date, $outletIds)
    {
        // Load orders with related payments
        $orders = Order::with('payments')
            ->whereIn('outlet_id', $outletIds)
            ->whereDate('order_date', $current_audit_date)
            ->where('status', 'settled')
            ->get();

        $groupedByOutlet = [];

        foreach ($orders as $order) {
            $outletId = $order->outlet_id;

            foreach ($order->payments as $payment) {
                $method = $payment->payment_method;

                if (!isset($groupedByOutlet[$outletId])) {
                    $groupedByOutlet[$outletId] = [
                        'methods' => [],
                        'total' => 0, // initialize outlet total
                    ];
                }

                if (!isset($groupedByOutlet[$outletId]['methods'][$method])) {
                    $groupedByOutlet[$outletId]['methods'][$method] = [
                        'total_amount' => 0,
                        'payment_count' => 0,
                        'orders' => collect()
                    ];
                }

                $groupedByOutlet[$outletId]['methods'][$method]['total_amount'] += $payment->amount;
                $groupedByOutlet[$outletId]['methods'][$method]['payment_count'] += 1;
                $groupedByOutlet[$outletId]['methods'][$method]['orders']->push($order);

                // Increment total for the outlet
                $groupedByOutlet[$outletId]['total'] += $payment->amount;
            }
        }

        // Deduplicate orders
        // foreach ($groupedByOutlet as $outletId => $methods) {
        //     foreach ($methods as $method => $data) {
        //         $groupedByOutlet[$outletId][$method]['orders'] = $data['orders']->unique('id')->values();
        //     }
        // }

        return [
            'sales' => $groupedByOutlet,
            'orders' => $orders,
            'total_sales' => $orders->sum('total_amount'),
        ];
    }
}
