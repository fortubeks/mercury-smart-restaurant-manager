@extends('rocker-theme.layouts.app')
@section('content')

<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title">{{ isset($menuItem) ? 'Edit Menu Item' : 'Add New Menu Item' }} [<a href="{{route('menu-items.import.form')}}">Bulk Import</a>]</h5>
        <hr>
        <div class="form-body mt-4">
            <form class="row g-3" id="form" method="POST" action="{{ isset($menuItem) ? route('menu-items.update', $menuItem->id) : route('menu-items.store') }}" enctype="multipart/form-data">
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
                            <div class="col-md-8 mb-3">
                                <label for="item-description" class="form-label">Item description</label>
                                <textarea name="description" class="form-control" id="item-description" cols="5" rows="1">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        @if(isset($menuItem))
                        @if($menuItem->is_combo)
                        <div class="form-group mt-3">
                            @if ($menuItem->components->isNotEmpty())
                            Combo Items:
                            <ul>
                                @foreach ($menuItem->components as $comboItem)
                                <li>
                                    {{ $comboItem->name }} –
                                    {{ number_format($comboItem->pivot->qty, 2) }} portions
                                    (Available[{{ $comboItem->calculateQuantity() }}])
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p>No ingredients added yet.</p>
                            @endif
                            <details class="mb-3">
                                <summary class="cursor-pointer font-semibold text-blue-600">Select Combo Items (Menu Items):</summary>
                                <div id="combo-items-container" class="mt-2 max-h-64 overflow-y-auto border p-2 rounded bg-gray-50">
                                    <table id="combo-items-table" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity Used</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($menuItems as $comboItem)
                                            @php
                                            $quantityNeeded = $menuItem->components->find($comboItem->id)?->pivot->qty ?? '';
                                            $formattedQty = is_numeric($quantityNeeded) ? number_format($quantityNeeded, 2, '.', '') : '';
                                            @endphp
                                            <tr>
                                                <td>
                                                    <label class="d-flex align-items-center gap-2">
                                                        <input class="form-check-input"
                                                            type="checkbox"
                                                            name="combo_items[{{ $comboItem->id }}][checked]"
                                                            {{ isset($menuItem) && $menuItem->components->contains($comboItem->id) ? 'checked' : '' }}>
                                                        {{ $comboItem->name }}
                                                    </label>
                                                </td>

                                                <td style="width: 140px;">
                                                    <input type="number"
                                                        name="combo_items[{{ $comboItem->id }}][quantity]"
                                                        inputmode="decimal"
                                                        min="0"
                                                        step="any"
                                                        placeholder="Qty used"
                                                        class="form-control form-control-sm"
                                                        value="{{ $formattedQty }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        </div>
                        @else
                        <div class="form-group mt-3">
                            @if ($menuItem->ingredients->isNotEmpty())
                            Ingredients:
                            <ul>
                                @foreach ($menuItem->ingredients as $ingredient)
                                <li>
                                    {{ $ingredient->name }} –
                                    {{ number_format($ingredient->pivot->quantity_needed, 2) }}
                                    {{ $ingredient->unit_measurement ?? '' }}
                                    (Available[{{ $ingredient->outletQtyForOutlet(outlet()->id) }}])
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p>No ingredients added yet.</p>
                            @endif
                            <details class="mb-3">
                                <summary class="cursor-pointer font-semibold text-blue-600">Select Ingredients (Store Items):</summary>
                                <div id="store-items-container" class="mt-2 max-h-64 overflow-y-auto border p-2 rounded bg-gray-50">
                                    <table id="storeItemsTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty Needed (g, ml or pcs)</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($storeItems as $storeItem)
                                            <tr>
                                                <td>
                                                    <label>
                                                        <input class="form-check-input" type="checkbox"
                                                            name="store_items[{{ $storeItem->id }}][checked]"
                                                            {{ isset($menuItem) && $menuItem->ingredients->contains($storeItem->id) ? 'checked' : '' }}>
                                                        {{ $storeItem->name }}
                                                    </label>
                                                </td>

                                                <td>
                                                    @php
                                                    $quantityNeeded = $menuItem->ingredients->find($storeItem->id)?->pivot->quantity_needed ?? '';
                                                    $formattedQty = is_numeric($quantityNeeded) ? number_format($quantityNeeded, 2, '.', '') : '';
                                                    @endphp

                                                    <input type="number"
                                                        name="store_items[{{ $storeItem->id }}][quantity_needed]"
                                                        class="form-control w-24"
                                                        step="any"
                                                        min="0"
                                                        inputmode="decimal"
                                                        placeholder="Qty needed"
                                                        value="{{ $formattedQty }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        </div>
                        @endif
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="card mt-4">
                            <h6 class="card-header white">Item Image</h6>
                            <div class="card-body">
                                <div class="mb-3">
                                    @if(isset($menuItem))
                                    @if($menuItem->featuredImage)
                                    <img src="{{ asset($menuItem->featuredImage->image_path) }}" alt="Featured Image" style="max-width: 100%; height: auto;">
                                    @else
                                    <img src="https://placehold.co/300x200" alt="No Image" style="max-width: 100%; height: auto;">
                                    @endif
                                    @endif
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

<script>
    window.addEventListener('load', function() {
        var items_table = $('#storeItemsTable').DataTable({
            pageLength: 20,
            searching: true,
            ordering: false, // keep original order
        });

        var combo_items_table = $('#combo-items-table').DataTable({
            pageLength: 20,
            searching: true,
            ordering: false, // keep original order
        });

        $('#form').on('submit', function(e) {

            // Collect all input/select fields EXCEPT checkboxes
            items_table.$('input:not([type="checkbox"]), select, textarea').each(function() {

                // If field is not currently in the DOM (due to pagination)
                if (!document.contains(this)) {

                    // Add a hidden field so the value is still submitted
                    $('<input>').attr({
                        type: 'hidden',
                        name: this.name,
                        value: $(this).val()
                    }).appendTo('#form');
                }
            });
            combo_items_table.$('input:not([type="checkbox"]), select, textarea').each(function() {

                // If field is not currently in the DOM (due to pagination)
                if (!document.contains(this)) {

                    // Add a hidden field so the value is still submitted
                    $('<input>').attr({
                        type: 'hidden',
                        name: this.name,
                        value: $(this).val()
                    }).appendTo('#form');
                }
            });
            items_table.$('input[type="checkbox"]').each(function() {

                // Checkbox fields NOT present in DOM (paginated out)
                if (!document.contains(this)) {

                    if ($(this).is(':checked')) {
                        // If it was checked → submit a hidden checked version
                        $('<input>').attr({
                            type: 'hidden',
                            name: this.name,
                            value: 1
                        }).appendTo('#form');
                    } else {
                        // If it was not checked → submit hidden value 0
                        $('<input>').attr({
                            type: 'hidden',
                            name: this.name,
                            value: 0
                        }).appendTo('#form');
                    }
                }
            });
            combo_items_table.$('input[type="checkbox"]').each(function() {

                // Checkbox fields NOT present in DOM (paginated out)
                if (!document.contains(this)) {

                    if ($(this).is(':checked')) {
                        // If it was checked → submit a hidden checked version
                        $('<input>').attr({
                            type: 'hidden',
                            name: this.name,
                            value: 1
                        }).appendTo('#form');
                    } else {
                        // If it was not checked → submit hidden value 0
                        $('<input>').attr({
                            type: 'hidden',
                            name: this.name,
                            value: 0
                        }).appendTo('#form');
                    }
                }
            });
            // // Loop over all inputs (even hidden ones)
            // items_table.$('input, select, textarea').each(function() {
            //     if (!$.contains(document, this)) {
            //         // Append hidden field with same name and value
            //         $('<input>').attr({
            //             type: 'hidden',
            //             name: this.name,
            //             value: $(this).val()
            //         }).appendTo('#form');
            //     }
            // });
            // combo_items_table.$('input[type="number"], input[type="text"], select').each(function() {
            //     if (!$.contains(document, this)) {
            //         // Append hidden field with same name and value
            //         $('<input>').attr({
            //             type: 'hidden',
            //             name: this.name,
            //             value: $(this).val()
            //         }).appendTo('#form');
            //     }
            // });
        });

        document.querySelectorAll('input[name^="store_items"][name$="[quantity_needed]"]').forEach(function(qtyInput) {

            qtyInput.addEventListener('input', function() {
                let row = qtyInput.closest('tr');
                let checkbox = row.querySelector('input[type="checkbox"]');

                if (qtyInput.value.trim() !== "") {
                    checkbox.checked = true;
                } else {
                    checkbox.checked = false;
                }
            });
        });
    });
</script>
@endsection