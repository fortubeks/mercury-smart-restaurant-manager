<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public $status;
    public $trueLabel;
    public $falseLabel;

    public function __construct($status, $trueLabel = 'Active', $falseLabel = 'Inactive')
    {
        $this->status = $status;
        $this->trueLabel = $trueLabel;
        $this->falseLabel = $falseLabel;
    }

    public function render()
    {
        return view('components.status-badge');
    }
}
