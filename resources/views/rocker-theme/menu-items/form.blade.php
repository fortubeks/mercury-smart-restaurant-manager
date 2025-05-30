@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($menuItem) ? 'Edit Menu Item' : 'Add New Menu Item' }}</h5>
                <hr>
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($menuItem) ? route('menu-items.update', $menuItem->id) : route('menu-items.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($menuItem))
                        @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row mt-3">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom01" class="form-label">Item Name</label>
                                        <input type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="validationCustom01" placeholder="Item Name" name="name"
                                            value="{{ old('name', isset($menuItem) ? $menuItem->name : '') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom02" class="form-label">Selling Price</label>
                                        <input type="number" min="0"
                                            class="form-control @error('price') is-invalid @enderror"
                                            id="validationCustom02" placeholder="Price" name="price"
                                            value="{{ old('price', isset($menuItem) ? $menuItem->price : '') }}">
                                        @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="note" class="form-label">Outlet</label>
                                        <select id="outlet_id" name="outlet_id" class="form-select selectpicker">
                                            @foreach (getModelList('outlets') as $outlet)
                                            <option value="{{ $outlet->id }}"
                                                {{ old('outlet_id', isset($menuItem) ? $menuItem->outlet_id : '') == $outlet->id ? 'selected' : '' }}>
                                                {{ $outlet->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4 mb-3">
                                        <label for="menu_category_id" class="form-label">Category</label>
                                        <select id="menu_category_id" name="menu_category_id" class="form-select selectpicker">
                                            <option value="">Select Category</option>
                                            @foreach (getModelList('menu-categories') as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('menu_category_id', isset($menuItem) ? $menuItem->menu_category_id : '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('menu_category_id')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="item-description">Item description</label><br>
                                            <textarea name="description" class="form-control" id="item-description" cols="5" rows="2">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <details class="mb-3">
                                        <summary class="cursor-pointer font-semibold text-blue-600">Select Ingredients (Store Items in current Outlet):</summary>
                                        <div id="store-items-container" class="mt-2 max-h-64 overflow-y-auto border p-2 rounded bg-gray-50">
                                            @foreach ($outletStoreItems as $item)
                                            <div class="input-group mb-3">
                                                <div class="input-group-text">
                                                    <label>
                                                        <input class="form-check-input" type="checkbox" name="store_items[{{ $item->id }}][checked]"
                                                            {{ isset($menuItem) && $menuItem->outletStoreItems->contains($item->id) ? 'checked' : '' }}>
                                                        {{ $item->storeItem->name }}
                                                    </label>
                                                </div>
                                                <input type="number" name="store_items[{{ $item->id }}][quantity_used]"
                                                    step="0.01" placeholder="Qty used" class="ml-2 w-24 form-control"
                                                    value="{{ isset($menuItem) && $menuItem->outletStoreItems->find($item->id)?->pivot?->quantity_used ?? '' }}">
                                            </div>
                                            @endforeach
                                        </div>
                                    </details>
                                </div>

                                <div class="form-group mt-3">
                                    <details class="mb-3">
                                        <summary class="cursor-pointer font-semibold text-blue-600">Select Combo Items (Menu Items):</summary>
                                        <div id="combo-items-container" class="mt-2 max-h-64 overflow-y-auto border p-2 rounded bg-gray-50">
                                            @foreach ($menuItems as $comboItem)
                                            <div class="input-group mb-3">
                                                <div class="input-group-text">
                                                    <label>
                                                        <input class="form-check-input" type="checkbox" name="combo_items[{{ $comboItem->id }}][checked]"
                                                            {{ isset($menuItem) && $menuItem->components->contains($comboItem->id) ? 'checked' : '' }}>
                                                        {{ $comboItem->name }}
                                                    </label>
                                                </div>
                                                <input type="number" name="combo_items[{{ $comboItem->id }}][quantity]"
                                                    step="0.01" placeholder="Qty used" class="ml-2 w-24 form-control"
                                                    value="{{ isset($menuItem) && $menuItem->components->find($comboItem->id)?->pivot->quantity }}">
                                            </div>
                                            @endforeach
                                        </div>
                                    </details>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mt-4">
                                    <h6 class="card-header white">Item Image</h6>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <input type="file" name="image" id="image"
                                                class="form-control @error('image') is-invalid @enderror">
                                            <label for="image" class="label">Select Image</label>
                                            @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card mt-4">
                                    <h6 class="card-header white">Options</h6>
                                    <div class="card-body text-success">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input type="checkbox" class="custom-control-input" id="is_available" name="is_available" value="1" {{ old('is_available', isset($menuItem) ? $menuItem->is_available : false) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_available">Make available?</label>
                                        </div>
                                    </div>
                                    <div class="card-body text-success">
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input type="checkbox" class="custom-control-input" id="combo" name="is_combo" value="1" {{ old('is_combo', isset($menuItem) ? $menuItem->is_combo : false) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="combo">Combo?</label>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <button class="btn btn-primary" type="submit">Publish</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>