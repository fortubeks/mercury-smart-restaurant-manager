<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\BankAccountTransaction;
use Illuminate\Support\Facades\DB;

class BankAccountService
{
    /**
     * Credit a payment to a bank account and create a transaction log.
     *
     * @param \App\Models\IncomingPayment $payment
     * @return void
     */
    public function credit($payment)
    {
        if (!$payment->bank_account_id) {
            return; // No bank account assigned
        }

        $bankAccount = BankAccount::find($payment->bank_account_id);

        if (!$bankAccount) {
            return; // Bank account not found
        }

        $bankAccount->balance += $payment->amount;
        $bankAccount->save();

        // Create transaction log
        $description = 'Payment for ' . class_basename($payment->payable_type) . ' #' . $payment->payable_id;

        BankAccountTransaction::create([
            'restaurant_id' => restaurantId(),
            'bank_account_id' => $bankAccount->id,
            'transaction_type' => 'credit',
            'amount' => $payment->amount,
            'description' => $description,
            'transaction_date' => $payment->date_of_payment,
            'transactionable_type' => get_class($payment),
            'transactionable_id' => $payment->id,
        ]);
    }

    /**
     * Debit an amount from a bank account and create a transaction log.
     *
     * @param \App\Models\OutgoingPayment $outgoingPayment
     * @return void
     * @throws \Exception
     */
    public function debit($outgoingPayment)
    {
        if (!$outgoingPayment->bank_account_id) {
            throw new \Exception('No bank account assigned');
        }

        $bankAccount = BankAccount::find($outgoingPayment->bank_account_id);

        if (!$bankAccount) {
            throw new \Exception('Bank account not found');
        }

        if ($bankAccount->balance < $outgoingPayment->amount) {
            throw new \Exception('Insufficient funds');
        }

        DB::transaction(function () use ($bankAccount, $outgoingPayment) {
            // Update balance
            $bankAccount->balance -= $outgoingPayment->amount;
            $bankAccount->save();

            // Transaction description
            $description = trim(($outgoingPayment->note ?? '') . '. Payment for ' . class_basename($outgoingPayment->payable_type) . ' #' . $outgoingPayment->payable_id, '. ');

            // Log transaction
            BankAccountTransaction::create([
                'restaurant_id' => restaurantId(),
                'bank_account_id' => $bankAccount->id,
                'transaction_type' => 'debit',
                'amount' => $outgoingPayment->amount,
                'description' => $description,
                'transaction_date' => $outgoingPayment->date_of_payment,
                'transactionable_type' => get_class($outgoingPayment),
                'transactionable_id' => $outgoingPayment->id,
            ]);
        });
    }
}
