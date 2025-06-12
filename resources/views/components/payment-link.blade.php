@php
$payable = $payment->payable;
$link = null;

if ($payable instanceof \App\Models\Order) {
$link = route('orders.show', $payable->id);
}
@endphp

@if ($link)
<a href="{{ $link }}" class="me-3">
    <i class='bx bx-show'></i>
</a>
@else
<span>N/A</span>
@endif