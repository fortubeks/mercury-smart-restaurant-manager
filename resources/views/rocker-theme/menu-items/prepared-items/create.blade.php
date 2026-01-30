@extends('rocker-theme.layouts.app')
@section('content')

<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title">Add New Prepared Menu Item </h5>
        <hr>
        <div class="form-body mt-4">
            <form method="POST" action="{{ route('prepared-outlet-menu-items.store') }}">
                @csrf

                <div class="mb-3">
                    <label>Menu Item</label>
                    <select name="menu_item_id" class="form-control" required>
                        @foreach($menuItems as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Prepared Quantity</label>
                    <input type="number"
                        step="0.01"
                        min="0"
                        name="qty"
                        class="form-control"
                        required>
                </div>

                <button class="btn btn-primary">
                    Add prepared quantity
                </button>

            </form>
        </div><!--end row-->
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection