@extends('rocker-theme.layouts.app')
@section('content')
<h5 class="mb-3">Move Store Items to Outlet</h5>
<div class="row mb-5">
    <div class="col-xl-12 mx-auto">
        <form action="{{ url('store/give-items') }}" method="POST">
            @csrf
            <div class="d-flex align-items-center mb-4 gap-3">
                <div class="col-md-3">
                    <select id="category_id" class="form-select" name="category_id">
                        <option value="">--Select Category--</option>
                        @foreach(getModelList('store-item-categories') as $storeItemCategory)
                        <option value="{{ $storeItemCategory->id }}" {{ request('category_id') == $storeItemCategory->id ? 'selected' : '' }}>
                            {{ $storeItemCategory->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="store_id" class="form-select" name="store_id">
                        <option value="">--Select Store--</option>
                        @foreach(getModelList('stores') as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="outlet_id" class="form-select" name="outlet_id">
                        <option value="">--Select Outlet--</option>
                        @foreach(getModelList('restaurant-outlets') as $outlet)
                        <option value="{{ $outlet->id }}" {{ request('outlet_id') == $outlet->id ? 'selected' : '' }}>
                            {{ $outlet->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="items-data-table" class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Balance in Stock</th>
                            <th>Quantity to give out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($storeItems as $storeItem)
                        <tr>
                            <td>{{ $storeItem->code }}</td>
                            <td>{{ $storeItem->name }}</td>
                            <td>{{ $storeItem->store_qty }}</td>
                            <td>
                                <input type="number" class="form-control" name="quantities[{{ $storeItem->id }}]" min="1" max="{{$storeItem->store_qty}}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <label for="recipient">Recipient Name:</label><br>
                    <input type="text" id="recipient" class="form-control" name="recipient">
                </div>
                <div class="col-md-6">
                    <label for="recipient">Notes:</label><br>
                    <input type="text" id="recipient" class="form-control" name="note">
                </div>
            </div>


            <div class="col-lg-12 mb-3 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Give Out</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

        const selects = ['category_id', 'store_id', 'outlet_id'];

        selects.forEach(id => {
            document.getElementById(id).addEventListener('change', function() {
                const category = document.getElementById('category_id').value;
                const store = document.getElementById('store_id').value;
                const outlet = document.getElementById('outlet_id').value;

                const params = new URLSearchParams();

                if (category) params.append('category_id', category);
                if (store) params.append('store_id', store);
                if (outlet) params.append('outlet_id', outlet);

                // Redirect to the same page with query params
                window.location.href = `{{ url('store/give-items') }}?` + params.toString();
            });
        });

        var items_table = $('#items-data-table').DataTable({
            lengthChange: false,
        });

        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection