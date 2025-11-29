@props([
'storeItems' => [],
'selectedItems' => [],
'prefix' => 'store_items'
])

<div class="recipe-builder">
    <h6 class="mb-2 font-semibold text-primary">Select Ingredients</h6>

    <div id="recipe-items-container" class="max-h-64 overflow-y-auto border p-2 rounded bg-gray-50">
        @foreach($storeItems as $item)
        @php
        $checked = isset($selectedItems[$item->id]);
        $quantity = $checked ? $selectedItems[$item->id]['quantity_needed'] : '';
        @endphp
        <div class="input-group mb-2 align-items-center">
            <div class="input-group-text">
                <input type="checkbox"
                    name="{{ $prefix }}[{{ $item->id }}][checked]"
                    class="form-check-input ingredient-checkbox"
                    data-id="{{ $item->id }}"
                    {{ $checked ? 'checked' : '' }}>
            </div>
            <span class="ingredient-name flex-grow-1 ps-2">{{ $item->name }}</span>
            <input type="number"
                name="{{ $prefix }}[{{ $item->id }}][quantity_needed]"
                class="form-control w-24 ms-2 ingredient-qty"
                min="0" step="any"
                placeholder="Qty"
                value="{{ $quantity }}"
                {{ $checked ? '' : 'disabled' }}>
            <span class="ms-2 text-muted">{{ $item->unit ?? '' }}</span>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.ingredient-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const id = this.dataset.id;
            const qtyInput = this.closest('.input-group').querySelector('.ingredient-qty');
            qtyInput.disabled = !this.checked;
            if (!this.checked) qtyInput.value = '';
        });
    });
</script>
@endpush