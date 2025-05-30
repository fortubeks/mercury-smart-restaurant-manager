@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="ms-auto">

        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="card-title">{{ isset($storeItem) ? 'Edit Store Item' : 'Add New Store Item' }}</h5>
                        <hr>
                        <form action="{{ isset($storeItem) ? route('store-items.update', $storeItem->id) : route('store-items.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if(isset($storeItem))
                            @method('PUT')
                            @endif
                            <div class="row mx-auto">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="item-category" class="form-label">Category <span class="required-asterisk">*</span></label>
                                    <select id="item-category" class="form-select form-control" name="item_category_id">
                                        <option value="">Select Category</option>
                                        @foreach (getModelList('store-item-categories') as $category)
                                        <option value="{{ $category->id }}" {{ old('item_category_id', isset($storeItem) ? $storeItem->item_category_id : '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'item-categories',
                                    ])
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label for="input1" class="form-label">Item Name <span class="required-asterisk">*</span></label>
                                    <input type="text" name="name" required value="{{ old('name', isset($storeItem) ? $storeItem->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="input1" placeholder="Name">
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'name',
                                    ])
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label class="form-label">Image</label>
                                    <input id="image" name="image" type="file" class="form-control @error('image') is-invalid @enderror" placeholder="Image">
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'image',
                                    ])
                                </div>
                            </div>
                            <div class="row mx-auto">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="unit_measurement" class="form-label">Unit Measurement</label>
                                    <select id="unit_measurement" name="unit_measurement" class="form-select form-control @error('unit_measurement') is-invalid @enderror">
                                        <option value="">Select Unit Measurement</option>
                                        @foreach (getModelList('unit-measurements') as $name => $value)
                                        <option value="{{ $value }}" {{ old('unit_measurement', isset($storeItem) ? $storeItem->unit_measurement : '') == $value ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @include('rocker-theme.alerts.error-feedback', ['field' => 'unit_measurement'])
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="qty" class="form-label">Quantity</label>
                                    <input id="qty" name="qty" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('qty') is-invalid @enderror" placeholder="Qty" value="{{ old('qty', isset($storeItem) ? $storeItem->qty : '') }}" @if(isset($storeItem)) disabled @endif>
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'qty',
                                    ])
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="cost_price" class="form-label">Cost Price</label>
                                    <input id="cost_price" name="cost_price" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('cost_price') is-invalid @enderror" placeholder="Cost Price" value="{{ old('cost_price', isset($storeItem) ? $storeItem->cost_price : '') }}">
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'cost_price',
                                    ])
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="selling_price" class="form-label">Selling Price <span class="required-asterisk">*</span></label>
                                    <input id="selling_price" name="selling_price" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('selling_price') is-invalid @enderror" placeholder="Selling Price" value="{{ old('selling_price', isset($storeItem) ? $storeItem->selling_price : '') }}">
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'selling_price',
                                    ])
                                </div>
                            </div>
                            <div class="row mx-auto">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="cost_price" class="form-label">Low Stock Alert</label>
                                    <input id="low_stock_alert" name="low_stock_alert" type="number" inputmode="decimal" min="0" step="any" class="form-control @error('low_stock_alert') is-invalid @enderror" placeholder="Low Stock Alert" value="{{ old('low_stock_alert', isset($storeItem) ? $storeItem->low_stock_alert : '') }}">
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'low_stock_alert',
                                    ])
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <label for="for_sale" class="form-label">For Sale <span class="required-asterisk">*</span></label>
                                    <select id="for_sale" class="form-select form-control" name="for_sale">
                                        <option value="">Select Status</option>
                                        @foreach ([1 => 'Yes', 0 => 'No'] as $value => $label)
                                        <option value="{{ $value }}" {{ old('for_sale', isset($storeItem) ? $storeItem->for_sale : '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @include('rocker-theme.alerts.error-feedback', [
                                    'field' => 'for_sale',
                                    ])
                                </div>
                                <div class="col-6 pb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" type="text" rows="1" cols="1" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ old('description') }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <button class="btn btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                @if(isset($storeItem))
                <div class="card radius-10 mt-4 mb-4">
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
                @endif
            </div>
        </div>
    </div>
</div>