<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\OutgoingPayment;
use App\Models\Purchase;

class OutgoingPaymentService
{
    public function createForPurchase(array $data)
    {
        $payment = OutgoingPayment::create([
            'payable_id' => $data['purchase_id'],
            'payable_type' => Purchase::class,
            'bank_account_id' => $data['bank_account_id'],
            'restaurant_id' => restaurantId(),
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'note' => $data['note'] ?? null,
            'date_of_payment' => $data['date_of_payment'],
        ]);

        (new BankAccountService)->debit($payment);

        return $payment;
    }

    public function createForExpense(array $data)
    {
        $payment = OutgoingPayment::create([
            'payable_id' => $data['expense_id'],
            'payable_type' => Expense::class,
            'bank_account_id' => $data['bank_account_id'],
            'restaurant_id' => restaurantId(),
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'note' => $data['note'] ?? null,
            'date_of_payment' => $data['date_of_payment'],
        ]);

        (new BankAccountService)->debit($payment);

        return $payment;
    }
}
