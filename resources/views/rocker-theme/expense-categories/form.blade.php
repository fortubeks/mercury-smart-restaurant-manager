@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($expenseCategory) ? 'Edit Expense Category' : 'Add New Expense Category' }}</h5>
                <hr>
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($expenseCategory) ? route('expense-categories.update', $expenseCategory->id) : route('expense-categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($expenseCategory))
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
                                            value="{{ old('name', isset($expenseCategory) ? $expenseCategory->name : '') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="parent_id" class="form-label">Parent Category</label>
                                        <select id="parent_id" name="parent_id" class="form-select selectpicker">
                                            <option value="">Select Category</option>
                                            @foreach (getModelList('expense-categories') as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('parent_id', isset($expenseCategory) ? $expenseCategory->parent_id : '') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($expenseCategory) ? 'Update Category' : 'Create Category' }}
                                    </button>
                                    <a href="{{ route('expense-categories.index') }}" class="btn btn-secondary">Cancel</a>
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
        $('#search-expenseCategory').focus();
        $('#search-expenseCategory').on('input', function() {
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