<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $bankAccounts = getModelList('bank-accounts');
        return theme_view('rocker-theme.bank-accounts.index')->with(compact('bankAccounts'));
    }

    public function show(BankAccount $bankAccount)
    {
        return theme_view('rocker-theme.bank-accounts.form')->with(compact('bankAccount'));
    }

    public function create()
    {
        return theme_view('rocker-theme.bank-accounts.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
        ]);
        BankAccount::create([
            'restaurant_id' => restaurantId(),
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'balance' => $request->balance ?: 0.00,
        ]);

        return redirect('bank-accounts')->with('success', 'Bank Account created successfully');
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
        ]);

        $bankAccount->update([
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);

        return redirect('bank-accounts')->with('success', 'Bank Account updated successfully');
    }

    public function destroy(BankAccount $bankAccount)
    {
        if ($bankAccount->transactions()->exists()) {
            return redirect('bank-accounts')->with('error', 'Bank Account cannot be deleted as it has transactions associated with it');
        }
        $bankAccount->delete();
        return redirect('bank-accounts')->with('success', 'Bank Account deleted successfully');
    }
}
