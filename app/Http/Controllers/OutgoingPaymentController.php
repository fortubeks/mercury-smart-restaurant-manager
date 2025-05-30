<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Services\OutgoingPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingPaymentController extends Controller
{
    public function storePurchasePayment(Request $request, OutgoingPaymentService $outgoingPaymentService)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'date_of_payment' => 'required',
            'bank_account_id' => 'required'
        ]);

        try {
            DB::transaction(function () use ($outgoingPaymentService, $request) {
                $outgoingPaymentService->createForPurchase($request->all());
            });

            return back()->with('success', 'Successfully added');
        } catch (\Exception $e) {
            // Optionally log the error
            \Log::error('Purchase payment failed: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Failed to process payment.' . $e->getMessage()]);
        }
    }
}
