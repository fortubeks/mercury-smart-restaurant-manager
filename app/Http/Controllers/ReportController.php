<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\Expense;
use App\Models\Hotel;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return theme_view('rocker-theme.reports.index');
    }

    public function downloadReport(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'report_type' => 'required|string',
        ]);

        if ($request->report_type === 'profit_loss') {
            $pdf = $this->generateProfitLossPDF($request->restaurant_id, $request->start_date, $request->end_date);
            return $pdf->download('profit_loss_report.pdf');
        }

        return back()->with('error', 'Invalid report type.');
    }

    public function getProfitLossData($restaurant_id, $start_date, $end_date)
    {
        $restaurant = Hotel::findOrFail($restaurant_id);
        $storeId = $restaurant->store->id;

        // Fetch sales and expenses data
        $sales = DailySale::where('restaurant_id', $restaurant_id)
            ->whereBetween('shift_date', [$start_date, $end_date])
            ->sum('final_total');

        $expenses = Expense::where('restaurant_id', $restaurant_id)
            ->whereBetween('expense_date', [$start_date, $end_date])
            ->sum('amount');

        $purchases = Purchase::where('store_id', $storeId)
            ->whereBetween('purchase_date', [$start_date, $end_date])
            ->sum('amount');

        // Calculate profit values
        $gross_profit = $sales - $purchases;
        $operating_profit = $gross_profit - $expenses;
        $non_operating_income = 0; // Placeholder
        $non_operating_expense = 0; // Placeholder
        $net_profit_loss = $operating_profit + $non_operating_income - $non_operating_expense;

        return [
            'restaurant' => $restaurant,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'operating_income' => $sales,
            'operating_expense' => $expenses,
            'cost_of_goods_sold' => $purchases,
            'gross_profit' => $gross_profit,
            'non_operating_income' => $non_operating_income,
            'non_operating_expense' => $non_operating_expense,
            'net_profit_loss' => $net_profit_loss,
            'operating_profit' => $operating_profit,
        ];
    }

    public function generateProfitLossPDF($restaurant_id, $start_date, $end_date)
    {
        $data = $this->getProfitLossData($restaurant_id, $start_date, $end_date);

        $pdf = Pdf::loadView('dashboard.reports.profit-loss-pdf', $data);

        return $pdf;
    }
}
