<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dashboardMetricsService = app('App\Services\DashboardMetricsService');
        $data = $dashboardMetricsService->superAdminHomeDashboardMetrics($request);

        $salesData = $data['sales'];
        $expensesData = $data['expenses'];
        $kpiMetrics = $data['kpiMetrics'];

        return theme_view('dashboard')->with(compact('salesData', 'expensesData', 'kpiMetrics'));
    }
}
