<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OutgoingPaymentLink extends Component
{
    /**
     * Create a new component instance.
     */
    public $outgoingPayment;

    public function __construct($outgoingPayment)
    {
        $this->outgoingPayment = $outgoingPayment;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.outgoing-payment-link');
    }
}
