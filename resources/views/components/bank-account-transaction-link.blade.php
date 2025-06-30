@php
$transactionable = $transaction->transactionable;
$link = null;
if($transactionable instanceof \App\Models\IncomingPayment) {
$payable = $transactionable->payable;

if ($payable instanceof \App\Models\Order) {
$link = route('orders.show', $payable->id);
} elseif ($payable instanceof \App\Models\Settlement) {
$nested = $payable->payable ?? null;

if ($nested instanceof \App\Models\Order) {
$link = route('orders.show', $nested->id);
}
}
}
elseif($transactionable instanceof \App\Models\OutgoingPayment) {
$payable = $transactionable->payable;
if ($payable instanceof \App\Models\Expense) {
$link = route('expenses.show', $payable->id);
} elseif ($payable instanceof \App\Models\Purchase) {
$link = route('purchases.show', $payable->id);
}

}
@endphp

@if ($link)
<a href="{{ $link }}" class="me-3">
    <i class='bx bx-show'></i>
</a>
@else
<span>N/A</span>
@endif