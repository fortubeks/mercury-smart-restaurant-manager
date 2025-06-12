<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaymentLink extends Component
{
    /**
     * Create a new component instance.
     */
    public $payment;

    public function __construct($payment)
    {
        $this->payment = $payment;
    }

    public function render(): View|Closure|string
    {
        return view('components.payment-link');
    }
}
