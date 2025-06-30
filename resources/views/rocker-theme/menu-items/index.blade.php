@extends('rocker-theme.layouts.app')
@section('content')

<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                Menu Items in <span class="text-primary">{{ $currentOutlet->name }}</span>
                <select id="outlet" class="form-select" data-outlet="{{ $currentOutlet->id }}">
                    @foreach (getModelList('outlets') as $outlet)
                    <option value="{{ $outlet->id }}" {{ ($currentOutlet->id == $outlet->id) ? 'selected' : '' }}>
                        {{ $outlet->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="ms-auto"><a href="{{ route('menu-items.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Item</a></div>
        </div>
        <div class="table-responsive">
            <table id="items-data-table" class="table">
                <thead class="table-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if ($menuItems->count())
                <tbody>
                    @foreach ($menuItems as $menuItem)
                    <tr>
                        <td>{{ $menuItem->name }}</td>
                        <td>
                            @if($menuItem->menuCategory)
                            {{ $menuItem->menuCategory->name }}
                            @else
                            <span class="badge bg-danger">No Category</span>
                            @endif
                        </td>
                        <td>
                            @if($menuItem->image)
                            <img style="width: 40px;" class="img-fluid item-image" src="{{ asset('storage/' . $menuItem->image) }}" alt=" Image">
                            @else
                            @endif
                        <td>₦{{ number_format($menuItem->price) }}</td>
                        <td>{{ $menuItem->quantity }}</td>
                        <td>
                            <div class="d-flex order-actions">
                                <a href="{{ route('menu-items.edit', $menuItem->id) }}">
                                    <i class='bx bxs-edit'></i>
                                </a>
                                <a class="ms-3" href="{{ route('menu-items.show', $menuItem->id) }}">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$menuItem->id}}" data-resource-url="{{url('menu-items')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <tbody>
                    <tr>
                        <td colspan="7">
                            <h6>No Result</h6>
                        </td>
                    </tr>
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>

@include('rocker-theme.layouts.partials.delete-modal')

<script>
    window.addEventListener('load', function() {

        var items_table = $('#items-data-table').DataTable({
            lengthChange: true,
        });
        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection