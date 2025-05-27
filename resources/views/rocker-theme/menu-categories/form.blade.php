@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($menuCategory) ? 'Edit Menu Category' : 'Add New Menu Category' }}</h5>
                <hr>
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($menuCategory) ? route('menu-categories.update', $menuCategory->id) : route('menu-categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($menuCategory))
                        @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mt-3">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom01" class="form-label">Name</label>
                                        <input type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="validationCustom01" placeholder="Name" name="name"
                                            value="{{ old('name', isset($menuCategory) ? $menuCategory->name : '') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="note" class="form-label">Outlet</label>
                                        <select id="outlet_id" name="outlet_id" class="form-select selectpicker">
                                            @foreach (getModelList('outlets') as $outlet)
                                            <option value="{{ $outlet->id }}"
                                                {{ old('outlet_id', isset($menuCategory) ? $menuCategory->outlet_id : '') == $outlet->id ? 'selected' : '' }}>
                                                {{ $outlet->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="menu_category_id" class="form-label">Parent Category</label>
                                        <select id="menu_category_id" name="menu_category_id" class="form-select selectpicker">
                                            <option value="">Select Category</option>
                                            @foreach (getModelList('menu-categories') as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('menu_category_id', isset($menuCategory) ? $menuCategory->menu_category_id : '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('menu_category_id')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($menuCategory) ? 'Update Category' : 'Create Category' }}
                                    </button>
                                    <a href="{{ route('menu-categories.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>
</div>
@include('rocker-theme.customers.partials.delete-modal')

<script>
    window.addEventListener('load', function() {
        $('#search-menuCategory').focus();
        $('#search-menuCategory').on('input', function() {
            var search = $(this).val();
            $.ajax({
                url: "{{ route('search.customers') }}",
                method: 'GET',
                data: {
                    search: search
                },
                success: function(response) {
                    $('#customers-table tbody').html(response);
                }
            });
        });

    });
</script>