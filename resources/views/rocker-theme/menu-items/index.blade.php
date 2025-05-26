@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

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
                    <table id="items-table" class="table">
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
                            @foreach ($menuItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if($item->category)
                                    {{ $item->category->name }}
                                    @else
                                    <span class="badge bg-danger">No Category</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->image)
                                    <img style="width: 40px;" class="img-fluid item-image" src="{{ asset('storage/' . $item->image) }}" alt=" Image">
                                    @else
                                    <img style="width: 40px;" class="img-fluid user-photo" src="{{ url('/assets/images/menu-item-placeholder.png') }}" alt="Image">
                                    @endif
                                <td>₦{{ number_format($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{ route('menu-items.edit', $item->id) }}">
                                            <i class='bx bxs-edit'></i>
                                        </a>
                                        <a class="ms-3" href="{{ route('menu-items.show', $item->id) }}">
                                            <i class='bx bx-show'></i>
                                        </a>
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
    </div>
</div>
<script>
    window.addEventListener('load', function() {

        var items_table = $('#items-data-table').DataTable({
            lengthChange: true,
            buttons: ['excel', 'pdf', 'print']
        });
        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');
    });
</script>