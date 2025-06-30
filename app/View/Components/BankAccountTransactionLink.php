<?php

namespace App\View\Components;

use App\Models\BankAccountTransaction;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BankAccountTransactionLink extends Component
{
    public $transaction;
    /**
     * Create a new component instance.
     */
    public function __construct(BankAccountTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.bank-account-transaction-link');
    }
}
