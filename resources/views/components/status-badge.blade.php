@php
$isTrue = $status === true || $status === 1 || $status === '1';
$label = $isTrue ? $trueLabel : $falseLabel;
$class = $isTrue ? 'bg-success' : 'bg-danger';
@endphp

<span class="badge {{ $class }}">{{ $label }}</span>

{{-- Usage example in a Blade template --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Active" falseLabel="Inactive" /> --}}

{{-- You can also use it with boolean values --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Enabled" falseLabel="Disabled" /> --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Online" falseLabel="Offline" /> --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Available" falseLabel="Unavailable" /> --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Confirmed" falseLabel="Pending" /> --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Yes" falseLabel="No" /> --}}
{{-- <x-status-badge :status="$user->is_active" trueLabel="Success" falseLabel="Failure" /> --}}