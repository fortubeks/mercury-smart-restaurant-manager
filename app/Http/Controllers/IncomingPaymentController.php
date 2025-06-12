<?php

namespace App\Http\Controllers;

use App\Models\IncomingPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomingPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Get filter values from the request
        $bankAccountId = $request->input('bank_account_id');
        $startDate = $request->input('start_date', date('Y-m-d', strtotime('-1 month')));
        $endDate = $request->input('end_date', date('Y-m-d'));

        // Start building the query
        $paymentsQuery = IncomingPayment::query()
            ->with(['bankAccount', 'payable']) // Ensure relationships are loaded
            ->where('restaurant_id', restaurantId()) // Filter only incoming payments
            ->where('payment_method', '<>', 'credit')
            ->orderByDesc('created_at'); // Order by latest payments

        // Apply bank account filter if selected
        if (!empty($bankAccountId)) {
            $paymentsQuery->where('bank_account_id', $bankAccountId);
        }

        // Apply date range filter
        if (!empty($startDate)) {
            $paymentsQuery->whereDate('date_of_payment', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $paymentsQuery->whereDate('date_of_payment', '<=', $endDate);
        }

        // Calculate sum for each bank with bank name
        $sumByBank = IncomingPayment::select('bank_accounts.account_name as bank_name', 'incoming_payments.bank_account_id', DB::raw('SUM(amount) as total'))
            ->join('bank_accounts', 'incoming_payments.bank_account_id', '=', 'bank_accounts.id') // Join with bank_accounts table
            ->where('incoming_payments.restaurant_id', restaurantId())
            ->where('incoming_payments.payment_method', '<>', 'credit')
            ->when($bankAccountId, fn($query) => $query->where('incoming_payments.bank_account_id', $bankAccountId))
            ->when($startDate, fn($query) => $query->whereDate('incoming_payments.date_of_payment', '>=', $startDate))
            ->when($endDate, fn($query) => $query->whereDate('incoming_payments.date_of_payment', '<=', $endDate))
            ->groupBy('incoming_payments.bank_account_id', 'bank_accounts.account_name') // Group by both bank_account_id and bank name
            ->get();

        // Calculate sum for each payment method
        $sumByIncomingPaymentMethod = IncomingPayment::select('payment_method', DB::raw('SUM(amount) as total'))
            ->where('restaurant_id', restaurantId())->where('payment_method', '<>', 'credit')
            ->when($bankAccountId, fn($query) => $query->where('bank_account_id', $bankAccountId))
            ->when($startDate, fn($query) => $query->whereDate('date_of_payment', '>=', $startDate))
            ->when($endDate, fn($query) => $query->whereDate('date_of_payment', '<=', $endDate))
            ->groupBy('payment_method')
            ->get();

        $sumByPayableType = IncomingPayment::select('payable_type', DB::raw('SUM(amount) as total'))
            ->where('restaurant_id', restaurantId())->where('payment_method', '<>', 'credit')
            ->when($bankAccountId, fn($query) => $query->where('bank_account_id', $bankAccountId))
            ->when($startDate, fn($query) => $query->whereDate('date_of_payment', '>=', $startDate))
            ->when($endDate, fn($query) => $query->whereDate('date_of_payment', '<=', $endDate))
            ->groupBy('payable_type')
            ->get();

        // Get paginated results
        $incomingPayments = $paymentsQuery->paginate(15)->appends($request->query());

        // Calculate total sum of filtered payments
        $totalIncomingPayments = $paymentsQuery->sum('amount');

        if ($request->ajax()) {
            return response()->json([
                'html' => theme_view('rocker-theme.incoming-payments.index', compact(
                    'incomingPayments',
                    'totalIncomingPayments',
                    'sumByBank',
                    'sumByPayableType',
                    'sumByIncomingPaymentMethod'
                ))->render(),
                'totalIncomingPayments' => number_format($totalIncomingPayments, 2),
            ]);
        }

        return theme_view('rocker-theme.incoming-payments.index', compact(
            'incomingPayments',
            'totalIncomingPayments',
            'sumByBank',
            'sumByPayableType',
            'sumByIncomingPaymentMethod',
        ));
    }

    public function show(IncomingPayment $payment)
    {
        return theme_view('rocker-theme.incoming-payments.show')->with(compact('payment'));
    }

    public function destroy(IncomingPayment $payment)
    {
        if (auth()->user()->role == 'Hotel_Owner' || auth()->user()->role == 'Manager') {

            if ($payment->payment_method == 'wallet') {
                $this->deleteWalletTransaction($payment);
            }
            $payment->delete();
            return back()->with('success', 'IncomingPayment deleted successfully');
        }

        return back()->with('error', 'You do not have permission to delete payments');
    }
}
