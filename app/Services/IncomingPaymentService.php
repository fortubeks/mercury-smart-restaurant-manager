<?php

namespace App\Services;

use App\Models\IncomingPayment;

class IncomingPaymentService
{
    /**
     * Process the incoming payment.
     *
     * @param array $paymentData
     * @return object $incomingPayment
     */
    public function processPayment($paymentData)
    {
        // Validate payment data
        //$this->validatePaymentData($paymentData);

        //if payment method is cash set the defaut cash account
        if ($paymentData['payment_method'] == 'cash') {
            //find the default bank account and set the $request->bank_account_id
            if ($paymentData['bank_account_id'] == null) {
                $defaultCashAccount = restaurant()->defaultCashBankAccount();
                $paymentData['bank_account_id'] = $defaultCashAccount->id;
            }
        }

        $incomingPayment = IncomingPayment::create($paymentData);

        //if mode of payment is wallet, debit the guest wallet
        // if ($payment->payment_method == 'wallet') {

        //     $debitedAmount = $payment->amount;

        //     //confirm the wallet balance is greater than the debited amount
        //     if ($wallet->balance < $debitedAmount) {
        //         //add flash message and return back
        //         return back()->with('error', 'Insufficient balance in wallet');
        //     }
        //     //if wallet type is guestWallet
        //     if (get_class($wallet) == GuestWallet::class) {

        //         $wallet->decrement('balance', $debitedAmount);
        //         $wallet->save();

        //         // Record transaction in guest wallet transactions 
        //         GuestWalletTransaction::create([
        //             'guest_wallet_id' => $wallet->id,
        //             'amount' => $debitedAmount,
        //             'transaction_type' => 'debit',
        //             'transaction_date' => $request->date_of_payment,
        //             'payment_id' => $payment->id, // Link to the payment record
        //             'description' => 'Wallet: ' . $description, // Optional description
        //             'balance' => $wallet->balance,
        //             'hotel_id' => hotelId()
        //         ]);
        //     }
        // }

        // Process the payment logic here (e.g., save to database, update order status)
        // For demonstration, we'll just return the processed data
        return $incomingPayment;
    }

    /**
     * Validate the payment data.
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    protected function validatePaymentData(array $data): void
    {
        if (empty($data['amount']) || !is_numeric($data['amount'])) {
            throw new \InvalidArgumentException('Invalid payment amount.');
        }

        if (empty($data['order_id'])) {
            throw new \InvalidArgumentException('Order ID is required.');
        }
    }
}
