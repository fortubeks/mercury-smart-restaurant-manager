<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StockAlert extends Component
{
    public $qty;
    public $lowStock;

    public function __construct($qty, $lowStock)
    {
        $this->qty = $qty;
        $this->lowStock = $lowStock;
    }

    public function render()
    {
        return view('components.stock-alert');
    }
}
