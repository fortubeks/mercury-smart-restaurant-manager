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
                        <h5>Expense Categories</h5>
                    </div>
                    <div class="ms-auto"><a href="{{ route('expense-categories.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Category</a></div>
                </div>
                <div class="table-responsive">
                    <table id="items-data-table" class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($expenseCategories->count())
                        <tbody>
                            @foreach ($expenseCategories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{ route('expense-categories.edit', $category->id) }}">
                                            <i class='bx bxs-edit'></i>
                                        </a>
                                        <a class="ms-3" href="{{ route('expense-categories.show', $category->id) }}">
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