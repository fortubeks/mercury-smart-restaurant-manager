<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankAccountTransaction;
use App\Services\BankTransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankAccountTransactionController extends Controller
{
    public function index(Request $request)
    {
        $bankAccountId = $request->input('bank_account_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $baseQuery = BankAccountTransaction::whereHas('bankAccount', function ($q) {
            $q->where('restaurant_id', restaurantId());
        });

        if (!empty($bankAccountId)) {
            $baseQuery->where('bank_account_id', $bankAccountId);
        }

        if (!empty($startDate)) {
            $baseQuery->whereDate('transaction_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $baseQuery->whereDate('transaction_date', '<=', $endDate);
        }

        // Clone base query to avoid modifying the pagination query
        $debitQuery = (clone $baseQuery)->where('transaction_type', 'debit');
        $creditQuery = (clone $baseQuery)->where('transaction_type', 'credit');

        $totalDebits = $debitQuery->sum('amount');
        $countDebits = $debitQuery->count();

        $totalCredits = $creditQuery->sum('amount');
        $countCredits = $creditQuery->count();

        // Get paginated transactions with eager loading
        $transactions = $baseQuery
            ->with(['bankAccount.restaurant'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends($request->query());

        return theme_view('bank-account-transactions.index', compact(
            'transactions',
            'totalDebits',
            'countDebits',
            'totalCredits',
            'countCredits'
        ));
    }

    public function create()
    {
        $cashAtHand = restaurant()->defaultCashBankAccount()->balance;
        $bankAccounts = restaurant()->bankAccounts;
        return theme_view('bank-account-transactions.create')->with(compact('cashAtHand', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $action = $request->input('submit_action'); // Get button value
        try {
            DB::beginTransaction();
            if ($action === 'cash_to_bank') {
                // Handle deposit logic
                app(\App\Http\Requests\CashToBankRequest::class);
                $toBankAccount = BankAccount::find($request->input('bank_account_id'));
                $fromBankAccount = restaurant()->defaultCashBankAccount();
                BankTransferService::transferBetweenBanks($request->input('amount'), $toBankAccount, $fromBankAccount, $request->input('transaction_date'));
            } elseif ($action === 'withdraw') {
                // Handle withdrawal logic
                app(\App\Http\Requests\BankAccountWithdrawalRequest::class);
                $bankAccount = BankAccount::find($request->input('bank_account_id'));
                BankTransferService::withdrawFromBank($request->input('amount'), $bankAccount, $request->input('transaction_date'), $request->input('description'));
            } elseif ($action === 'bank_to_bank') {
                // Handle transfer logic
                app(\App\Http\Requests\BankAccountTransferRequest::class);
                $fromBankAccount = BankAccount::find($request->input('from_bank_account_id'));
                $toBankAccount = BankAccount::find($request->input('to_bank_account_id'));
                BankTransferService::transferBetweenBanks($request->input('amount'), $toBankAccount, $fromBankAccount, $request->input('transaction_date'));
            } elseif ($action === 'bank_inflow') {
                // Handle transfer logic
                app(\App\Http\Requests\BankInflowRequest::class);
                $bankAccount = BankAccount::find($request->input('bank_account_id'));
                BankTransferService::addInflow($request->input('amount'), $bankAccount, $request->input('transaction_date'), $request->input('description'));
            } else {
                return back()->with('error', 'Invalid request');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error message
            logger($e->getMessage());
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

        return back()->with('success', ucfirst($action) . ' processed successfully');
    }
}
