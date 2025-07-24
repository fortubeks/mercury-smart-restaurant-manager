@extends('rocker-theme.layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">

            <div class="position-relative">
                <h6>
                    Store Items
                </h6>
            </div>
            <div class="ms-auto">
                <a href="{{route('store-item.import.form')}}" class="btn btn-sm btn-dark"><i class="bx bx-download mr-2"></i>Import/Update Store Items</a>
                <a href="{{route('store-item.download-existing')}}" class="btn btn-sm btn-dark"><i class="bx bx-upload mr-2"></i>Export Store Items</a>
                <a href="{{ url('store/give-items?type=food') }}" class="btn btn-sm btn-dark"><i class="bx bx-restaurant mr-2"></i>Give Out Items</a>
                <a href="{{ route('store-items.create') }}" class="btn btn-sm btn-dark"><i class="bx bx-plus-circle"></i>Add New Item</a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="store-items-data-table" class="table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Stock Balance</th>
                        <th>Unit</th>
                        <th>For Sale</th>
                        <th>Store</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($storeItems as $storeItem)
                    <tr>
                        <td>{{ $storeItem->code }}</td>
                        <td>
                            @if($storeItem->image)
                            <img style="width: 40px;" class="img-fluid item-image" src="{{ asset('storage/' . $storeItem->image) }}" alt=" Image">
                            @endif
                        </td>
                        <td>{{ $storeItem->name }}</td>
                        <td>{{ $storeItem->category() }}</td>
                        <x-stock-alert :qty="$storeItem->total_qty" :low-stock="$storeItem->low_stock_alert" />
                        <td>{{ $storeItem->unit_measurement }}</td>
                        <td>{{ $storeItem->for_sale ? 'Yes' : 'No' }}</td>
                        <td>{{ $storeItem->stores->pluck('name')->join(', ') }}</td>
                        <td>
                            <div class="d-flex order-actions">
                                <a href="{{ route('store-items.edit', $storeItem) }}" class="ms-3">
                                    <i class='bx bxs-edit'></i>
                                </a>
                                <a href="{{ route('store-items.show', $storeItem) }}" class="ms-3">
                                    <i class='bx bxs-show'></i>
                                </a>
                                <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$storeItem->id}}" data-resource-url="{{url('store-items')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <h6>No Store Items. <a href="{{ url('store-item/import') }}">Import Now</a></h6>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('rocker-theme.layouts.partials.delete-modal')

<script>
    window.addEventListener('load', function() {

        var store_items_table = $('#store-items-data-table').DataTable({
            lengthChange: false,
            order: [
                [4, 'asc']
            ],
        });
        store_items_table.buttons().container().appendTo('#store-items-data-table_wrapper .col-md-6:eq(0)');

    });
</script>
@endsection