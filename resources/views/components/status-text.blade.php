@php
$colorClass = ($status == '1' || $status === true || $status === 'active') ? 'text-success' : 'text-danger';
$text = ($status == '1' || $status === true || $status === 'active') ? $trueLabel : $falseLabel;
@endphp

<span class="{{ $colorClass }}">{{ $text }}</span>

{{-- Usage example in a Blade template --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Active" falseLabel="Inactive" /> --}}

{{-- You can also use it with boolean values --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Enabled" falseLabel="Disabled" /> --}}

{{-- <x-status-text :status="$user->is_active" trueLabel="Online" falseLabel="Offline" /> --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Available" falseLabel="Unavailable" /> --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Confirmed" falseLabel="Pending" /> --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Yes" falseLabel="No" /> --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Success" falseLabel="Failure" /> --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Active" falseLabel="Inactive" /> --}}
{{-- <x-status-text :status="$user->is_active" trueLabel="Enabled" falseLabel="Disabled" /> --}}