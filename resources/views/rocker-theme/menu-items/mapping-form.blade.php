@extends('rocker-theme.layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Map Menu Items to Store Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form action="{{ route('menu-items.map.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <table class="table-auto w-100 border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Menu Item</th>
                                <th class="border px-4 py-2">Store Items</th>
                                <th class="border px-4 py-2">Quantity Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menuItems as $menuItem)
                            <tr>
                                <td class="border px-4 py-2">{{ $menuItem->name }}</td>
                                <td class="border px-4 py-2">
                                    <select name="mappings[{{ $menuItem->id }}]" class="form-select">
                                        <option value="">Select Store Item</option>
                                        @foreach($outletStoreItems as $outletStoreItem)
                                        <option value="{{ $outletStoreItem->id }}"
                                            {{ $menuItem->outletStoreItems->contains($outletStoreItem->id) ? 'selected' : '' }}>
                                            {{ $outletStoreItem->storeItem->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number"
                                        name="store_items[{{ $menuItem->id }}][quantity_used]"
                                        step="0.01"
                                        placeholder="Qty used"
                                        class="ml-2 w-24 form-control"
                                        value="{{ $menuItem->outletStoreItems->firstWhere('id', $menuItem->outletStoreItems->first()?->id)?->pivot?->quantity_used ?? '' }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Save Mappings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection