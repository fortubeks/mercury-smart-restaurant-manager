<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'contact_person_name',
        'contact_person_phone',
        'bank_account_name',
        'bank_name',
        'bank_sort_code',
        'bank_account_no',
        'email',
        'address'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getBalance()
    {
        $balance = $this->getTotalSupplyAmount() - $this->getTotalPaymentsAmount();
        return $balance;
    }

    public function getTotalSupplyAmount()
    {
        $total = $this->getTotalExpensesAmount() + $this->getTotalPurchasesAmount();
        return $total;
    }

    public function getTotalExpensesAmount()
    {
        $amount = Expense::where('supplier_id', $this->id)->sum('amount');
        return $amount;
    }

    public function getTotalPurchasesAmount()
    {
        $amount = Purchase::where('supplier_id', $this->id)->sum('total_amount');
        return $amount;
    }

    public function getTotalPaymentsAmount()
    {
        return $this->outgoingPayments()->sum('amount');
    }

    // In Supplier model
    public function outgoingPayments()
    {
        return $this->hasMany(OutgoingPayment::class);
    }

    public function getSumOfNewBillsByMonth($month)
    {
        //get all expenses related to supplier
        $sum = Expense::where('supplier_id', $this->id)
            ->whereMonth('expense_date', '=', $month)->sum('amount');
        return $sum;
    }

    public function getTotalSupplyAmountByMonth($month, $year)
    {
        $sum = Expense::where('supplier_id', $this->id)
            ->whereMonth('expense_date', '=', $month)
            ->whereYear('expense_date', '=', $year)->sum('amount');
        return $sum;
    }

    public function getAmountOwingBeforeAParticularMonth($month)
    {
        //get all expenses with status not paid or paid part before the month and calculate the outstanding
        $paymentsAgainstTheExpenses = 0;
        $sumOfAllBillsForTheMonth = $this->getSumOfNewBillsByMonth($month);
        $expenses = Expense::where('supplier_id', $this->id)
            ->whereMonth('expense_date', '<', $month)->get();

        foreach ($expenses as $expense) {
            //get payments
            foreach ($expense->payments as $payment) {
                $paymentsAgainstTheExpenses += $payment->amount;
            }
        }
        return $sumOfAllBillsForTheMonth - $paymentsAgainstTheExpenses;
    }

    public function getTotalOwingAsAtAParticularMonth($month)
    {

        return ($this->getAmountOwingBeforeAParticularMonth($month)
            + $this->getSumOfNewBillsByMonth($month)) - $this->getSumOfPaymentsMadeInAParticularMonth($month);
    }

    public function getSumOfPaymentsMadeInAParticularMonth($month)
    {
        $payments = $this->outgoingPayments()
            ->whereMonth('expense_payments.date_of_payment', '=', $month)
            ->select('expense_payments.amount')->distinct()->sum('expense_payments.amount');
        return $payments;
    }
}
