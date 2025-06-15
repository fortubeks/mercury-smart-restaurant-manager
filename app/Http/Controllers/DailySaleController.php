<?php

namespace App\Http\Controllers;

use App\Models\DailySale;
use App\Models\DailySaleOutlet;
use App\Models\Order;
use App\Models\OutgoingPayment;
use App\Services\DailySaleComputationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailySaleController extends Controller
{
    public function index(DailySale $model)
    {
        return theme_view('rocker-theme.daily-sales.index', [
            'sales' => $model->where('restaurant_id', restaurantId())->latest()->get()
        ]);
    }

    public function create(DailySaleComputationService $dailySaleComputationService)
    {
        $restaurant = restaurant();
        $latestRecord = DailySale::where('restaurant_id', $restaurant->id)->latest()->first();
        //if there is no record, set the current audit date to today
        $current_audit_date = auth()->user()->current_shift;

        if ($latestRecord && $latestRecord->exists()) {
            $last_audited_date = $latestRecord->shift_date;
            // Add 1 day to the last audited date to get the current day audit date
            $current_audit_date = Carbon::createFromFormat('Y-m-d', $last_audited_date)->addDay()->toDateString();
        }

        //get the defaultOutlets
        // Get all outlets for the restaurant
        $outletIds = $restaurant->outlets()->pluck('id');

        $dailySaleInformation = $dailySaleComputationService->computeSales($current_audit_date, $outletIds);
        $sales = $dailySaleInformation['sales'];
        $orders = $dailySaleInformation['orders'];
        //get outgoing payments
        $outgoingPaymentsQuery = OutgoingPayment::with('payable')->where('restaurant_id', restaurantId())
            ->whereDate('date_of_payment', $current_audit_date)->orderBy('date_of_payment', 'desc');
        $outgoingPayments = $outgoingPaymentsQuery->get();

        $cashAccount = $restaurant->defaultCashBankAccount();
        $cashAccountBalance = $cashAccount->balance;

        $totalCashOutflows = (clone $outgoingPaymentsQuery)
            ->where('bank_account_id', $cashAccount->id)
            ->sum('amount');


        //get the most recent DailySales record and get the cash_account_balance
        $previousDayCashBalance = $restaurant->dailySales()->latest()->first() ?
            $restaurant->dailySales()->latest()->first()->closing_balance : 0;


        //get outlet names
        foreach ($sales as $outletId => $methods) {
            $outlet = $restaurant->outlets()->find($outletId);
            if ($outlet) {
                $sales[$outletId]['outlet_name'] = $outlet->name;
            }
        }

        return theme_view('rocker-theme.daily-sales.create')->with([
            'sales' => $sales,
            'orders' => $orders,
            'current_audit_date' => $current_audit_date,
            'restaurant' => $restaurant,
            'outgoingPayments' => $outgoingPayments,
            'previousDayCashBalance' => $previousDayCashBalance,
            'cashAccountBalance' => $cashAccountBalance,
            'totalCashOutflows' => $totalCashOutflows
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_date' => 'required|date',
            'closing_balance' => 'required'
        ]);

        $restaurant = restaurant();
        $shiftDate = $request->input('shift_date');

        // Prevent duplicate daily sale for the same date
        $existing = DailySale::where('restaurant_id', $restaurant->id)
            ->where('shift_date', $shiftDate)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors(['shift_date' => 'A daily sale record for this date already exists.']);
        }

        try {
            DB::connection()->beginTransaction();

            $dailySale = DailySale::create([
                'restaurant_id' => $restaurant->id,
                'shift_date' => $shiftDate,
                'cash' => $request->total_cash,
                'pos' => $request->total_pos,
                'transfer' => $request->total_transfer,
                'wallet' => $request->total_wallet,
                'credit' => $request->total_credit,
                'total' => $request->total,
                'opening_balance' => $request->closing_balance,
                'expected_cash_balance' => $request->expected_cash_balance,
                'cash_outflow' => $request->cash_outflow,
                'closing_balance' => $request->closing_balance,
                'audited_by' => auth()->id(),
            ]);

            foreach ($request->outlet_id as $key => $outlet_id) {
                DailySaleOutlet::create([
                    'outlet_id' => $outlet_id,
                    'daily_sale_id' => $dailySale->id,
                    'cash' => $request->outlet_cash[$key],
                    'pos' => $request->outlet_pos[$key],
                    'transfer' => $request->outlet_transfer[$key],
                    'wallet' => $request->outlet_wallet[$key],
                    'credit' => $request->outlet_credit[$key],
                    'total' => $request->outlet_total[$key],
                ]);
            }

            DB::connection()->commit();
        } catch (\Exception $e) {
            DB::connection()->rollBack();
            logger($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to save daily sale: ' . $e->getMessage()]);
        }

        return redirect()->route('daily-sales.index')->with('success', 'Daily sale record created successfully.');
    }

    public function show(DailySale $dailySale)
    {
        $restaurant = restaurant();
        $outletSales = $dailySale->outletSales()->with('outlet')->get();
        $outgoingPayments = OutgoingPayment::with('payable')
            ->where('restaurant_id', $restaurant->id)
            ->whereDate('date_of_payment', $dailySale->shift_date)
            ->orderBy('date_of_payment', 'desc')
            ->get();
        $currentAuditDate = $dailySale->shift_date;
        $lastAudit = DailySale::where('restaurant_id', $restaurant->id)->latest()->first();
        $lastAuditedDate = $lastAudit->shift_date;
        $firstAudit = DailySale::where('restaurant_id', $restaurant->id)->orderBy('shift_date', 'asc')->first();
        $is_latest = false;
        $firstAuditedDate = $firstAudit->shift_date;
        $is_latest = false;
        $previousDayCashBalance = restaurant()->dailySales()->where('shift_date', '<', $currentAuditDate)->latest()->first() ?
            restaurant()->dailySales()->where('shift_date', '<', $currentAuditDate)->latest()->first()->cash_account_balance : 0;
        $orders = Order::with('payments', 'outlet')
            ->whereDate('order_date', $currentAuditDate)
            ->where('status', 'settled')
            ->get();


        // If the latest audit record's ID matches the $auditId, it means it's the latest
        if ($lastAudit && $lastAudit->id === $dailySale->id) {
            $is_latest = true;
        }

        return theme_view('rocker-theme.daily-sales.show', [
            'dailySale' => $dailySale,
            'outletSales' => $outletSales,
            'outgoingPayments' => $outgoingPayments,
            'restaurant' => $restaurant,
            'lastAuditedDate' => $lastAuditedDate,
            'is_latest' => $is_latest,
            'firstAuditedDate' => $firstAuditedDate,
            'previousDayCashBalance' => $previousDayCashBalance,
            'orders' => $orders
        ]);
    }

    public function destroy(DailySale $daily_sale)
    {
        $daily_sale->delete();
        return redirect('daily-sales')->with('success', 'Records deleted successfully');
    }
}
