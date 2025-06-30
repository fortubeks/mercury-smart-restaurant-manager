<h6>
    @if ($order->deliveryRider)
    <a href="{{ route('delivery-riders.show', $order->delivery_rider_id) }}">
        Delivery Rider: {{ $order->deliveryRider->name }}
    </a>
    @else
    <div class="d-flex align-items-center gap-2">
        <select id="rider_select_{{ $order->id }}" class="rider-select form-select" data-order-id="{{ $order->id }}">
            <option value="">-- Select Rider --</option>
            @foreach($availableRiders as $rider)
            <option value="{{ $rider->id }}">{{ $rider->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
</h6>