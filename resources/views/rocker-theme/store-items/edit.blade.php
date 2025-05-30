@extends('dashboard.layouts.app')

@section('contents')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{$store_item->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('store-items.index') }}" class="btn btn-dark">View All Items</a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <div class="card-title">
                        <h5 class="mb-0">Item Details</h5>
                    </div>
                    <hr>
                    <!--include flash message manually if you wish -->
                    <form action="{{ route('store-items.update', $store_item->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mx-auto">
                            <!-- Hidden input field for item ID -->
                            <input type="hidden" name="store_item_id" value="{{ $store_item->id }}">

                            <!-- Category select -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="item-category" class="form-label">Category</label>
                                <select id="item-category" class="form-select form-control" name="item_category_id">
                                    <option value="">Select Category</option>
                                    @foreach (getModelList('item-categories') as $category)
                                    <option value="{{ $category->id }}" {{ old('item_category_id', $store_item->item_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @include('alerts.error-feedback', [
                                'field' => 'item-categories',
                                ])
                            </div>

                            <!-- Item SubCategory select -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="item-subcategory" class="form-label">Item SubCategory</label>
                                <select id="item-subcategory" class="form-select form-control" name="item_sub_category_id">
                                    <option value="">Select Category</option>
                                    @foreach (getModelList('item-sub_categories') as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('item_sub_category_id', $store_item->item_sub_category_id) == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @include('alerts.error-feedback', [
                                'field' => 'item-sub_categories',
                                ])
                            </div>

                            <!-- Item Name input -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="input1" class="form-label">Item Name</label>
                                <input type="text" name="name" required value="{{ old('name', $store_item->name) }}" class="form-control @error('name') is-invalid @enderror" id="input1" placeholder="Name">
                                @include('alerts.error-feedback', [
                                'field' => 'name',
                                ])
                            </div>

                            <!-- Image input -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label class="form-label">Image</label>
                                <input id="image" name="image" type="file" class="form-control @error('image', $store_item->image) is-invalid @enderror" placeholder="Image">
                                @include('alerts.error-feedback', [
                                'field' => 'image',
                                ])
                            </div>

                            <!-- Description textarea -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" type="text" rows="1" cols="1" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ old('description', $store_item->description) }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Unit Measurement select -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="unit_measurement" class="form-label">Unit Measurement</label>
                                <select id="unit_measurement" name="unit_measurement" class="form-select form-control @error('unit_measurement') is-invalid @enderror">
                                    <option value="">Select Unit Measurement</option>
                                    @php
                                    $measurements = ['kg' => 'Kilogram (kg)', 'pcs' => 'Pieces (pcs)'];
                                    @endphp
                                    @foreach ($measurements as $key => $label)
                                    <option value="{{ $key }}" {{ old('unit_measurement', $store_item->unit_measurement) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @include('alerts.error-feedback', ['field' => 'unit_measurement'])
                            </div>

                            <!-- Quantity input -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input id="qty" readonly name="qty" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('qty') is-invalid @enderror" placeholder="Qty" value="{{ old('qty', $store_item->qty) }}">
                                @include('alerts.error-feedback', ['field' => 'qty'])
                            </div>

                            <!-- Cost Price input -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="cost_price" class="form-label">Cost Price</label>
                                <input id="cost_price" name="cost_price" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('cost_price') is-invalid @enderror" placeholder="Cost Price" value="{{ old('cost_price', $store_item->cost_price) }}">
                                @include('alerts.error-feedback', ['field' => 'cost_price'])
                            </div>

                            <!-- Selling Price input -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="selling_price" class="form-label">Selling Price</label>
                                <input id="selling_price" name="selling_price" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('selling_price') is-invalid @enderror" placeholder="Selling Price" value="{{ old('selling_price', $store_item->selling_price) }}">
                                @include('alerts.error-feedback', ['field' => 'selling_price'])
                            </div>

                            <!-- Low Stock Alert input -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="low_stock_alert" class="form-label">Low Stock Alert</label>
                                <input id="low_stock_alert" name="low_stock_alert" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('low_stock_alert') is-invalid @enderror" placeholder="Low Stock Alert" value="{{ old('low_stock_alert', $store_item->low_stock_alert) }}">
                                @include('alerts.error-feedback', ['field' => 'low_stock_alert'])
                            </div>

                            <!-- For Sale select -->
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                <label for="for_sale" class="form-label">For Sale</label>
                                <select id="for_sale" class="form-select form-control" name="for_sale">
                                    <option value="">Select Status</option>
                                    @foreach ([1 => 'Yes', 0 => 'No'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('for_sale', $store_item->for_sale) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @include('alerts.error-feedback', ['field' => 'for_sale'])
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 pb-3 mt-3">
                                <!-- Sub Items Section -->
                                <div class="mb-3">
                                    <label class="form-label">Sub Items</label>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="sub-items-container">
                                            <!-- Sub-items will be appended here dynamically -->
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-outline-primary" id="add-sub-item">Add Sub Item</button>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 pb-3 mt-3">
                                <button data-bs-toggle="modal" data-bs-target="#deleteItemModal" type="button" class="btn btn-danger"><i class="bx bx-trash"></i>
                                    Delete</button>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 pb-3 mt-3">

                                <button class="btn btn-primary w-100" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card radius-10">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h5 class="mb-0">Report Damaged Item</h5>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <form method="post" action="{{ route('store.reportdamaged') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <label for="category">Qty</label>
                                        <input name="qty" class="form-control" placeholder="Quantity reported" type="number" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category">Date Reported</label>
                                        <input name="date" class="form-control date-format" data-toggle="flatpickr" type="date" required value="{{now()}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category">Outlet</label>
                                        <select id="outlet_id" class="form-select form-control" name="outlet_id">
                                            <option value="">--Store--</option>
                                            @foreach (getModelList('outlets') as $outlet)
                                            <option value="{{ $outlet->id }}">
                                                {{ $outlet->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="category">Reason</label>
                                        <input type="text" name="reason" class="form-control" placeholder="Reason">
                                    </div>
                                    <div class="col-md-1 mt-4">
                                        <input type="hidden" name="store_item_id" value="{{$store_item->id}}">
                                        <button class="btn btn-sm btn-secondary w-100" type="submit">Report</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--end row-->
@include('dashboard.store.store-items.partials.delete-modal')
@endsection
{{--
    <script>
        window.addEventListener('load', function() {
            $('#item-category').change(function() {
                var categoryId = $(this).val();

                // Clear existing options in subcategory dropdown
                $('#item-subcategory').empty();

                // If 'Select' is chosen, no need to fetch subcategories
                if (categoryId === 'Select') {
                    return;
                }

                // Fetch subcategories based on selected category
                $.ajax({
                    url: '/fetch-subcategories/' + categoryId,
                    type: 'GET',
                    success: function(data) {
                        // Populate subcategory dropdown with fetched subcategories
                        $.each(data, function(key, value) {
                            $('#item-subcategory').append('<option value="' + value
                                .id + '">' +
                                value.name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script> --}}

<script>
    window.addEventListener('load', function() {
        $('#add-sub-item').click(function() {
            let subItemRow = `
            <tr>
                <td>
                    <select name="sub_items[]" class="form-control" required>
                        <option value="">Select Item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="sub_item_quantities[]" class="form-control" placeholder="e.g., 2oz" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-sub-item">Remove</button>
                </td>
            </tr>
        `;
            $('#sub-items-container').append(subItemRow);
        });

        $(document).on('click', '.remove-sub-item', function() {
            $(this).closest('tr').remove();
        });
    });
</script>