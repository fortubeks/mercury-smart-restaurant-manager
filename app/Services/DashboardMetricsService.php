<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardMetricsService
{
    public function superAdminHomeDashboardMetrics($request)
    {
        // Sales & Expenses use last 12 months or custom range
        $salesStart = $request->input('start_date') ?? now()->subMonths(12)->startOfMonth();
        $salesEnd = $request->input('end_date') ?? now()->endOfMonth();

        $sales = $this->salesLast12Months($salesStart, $salesEnd);
        $expenses = $this->expensesLast12Months($salesStart, $salesEnd);

        // KPI Metrics always use today
        $kpiStart = $request->input('start_date') ?? now()->startOfDay();
        $kpiEnd = $request->input('end_date') ?? now()->endOfDay();

        $kpiMetrics = $this->kpiMetrics($kpiStart, $kpiEnd);

        return [
            'sales' => $sales,
            'expenses' => $expenses,
            'kpiMetrics' => $kpiMetrics,
        ];
    }

    public function salesLast12Months($startDate, $endDate)
    {
        $sales = DB::table('daily_sales')->select(
            DB::raw('SUM(total) as total'),
            DB::raw("DATE_FORMAT(shift_date, '%Y-%m') as month") // Format as YYYY-MM
        )
            ->whereBetween('shift_date', [$startDate, $endDate])
            ->where('restaurant_id', restaurantId())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');


        // Generate the last 6 months (even if there is no sales data)
        $months = [];
        $salesData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $monthKey = $date->format('Y-m'); // YYYY-MM
            $monthLabel = $date->format('M'); // Jan, Feb, etc.

            $months[] = $monthLabel;
            $salesData[] = $sales[$monthKey] ?? 0;
        }

        return [
            'months' => $months,
            'salesData' => $salesData,
        ];
    }

    public function expensesLast12Months($startDate, $endDate)
    {
        $expenses = DB::table('expenses')->select(
            DB::raw('SUM(amount) as total'),
            DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month") // Format as YYYY-MM
        )
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->where('restaurant_id', restaurantId())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Generate the last 6 months (even if there is no expenses data)
        $months = [];
        $expensesData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $monthKey = $date->format('Y-m'); // YYYY-MM
            $monthLabel = $date->format('M'); // Jan, Feb, etc.

            $months[] = $monthLabel;
            $expensesData[] = $expenses[$monthKey] ?? 0;
        }

        return [
            'months' => $months,
            'expensesData' => $expensesData,
        ];
    }

    public function kpiMetrics($startDate, $endDate)
    {
        $baseQuery = Order::whereHas('outlet', function ($q) {
            $q->where('restaurant_id', restaurantId());
        })->whereBetween('order_date', [$startDate, $endDate]);

        return [
            'todayOrders' => (clone $baseQuery)->sum('total_amount'),
            'todayCustomers' => (clone $baseQuery)->distinct('customer_id')->count('customer_id'),
            'todayOrdersCount' => (clone $baseQuery)->count(),
            'todayDeliveries' => (clone $baseQuery)->where('order_type', 'delivery')->count(),
        ];
    }
}
