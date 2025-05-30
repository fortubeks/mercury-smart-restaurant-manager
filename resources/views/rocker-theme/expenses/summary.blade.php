@extends('dashboard.layouts.app')

<style>
    .expense-row:hover {
        background-color: #f1f1f1;
    }
</style>

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
                    <li class="breadcrumb-item active" aria-current="page">Expenses</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('expenses.index') }}" class="btn btn-dark">View Dashboard</a>
            <a href="{{ route('expenses.create') }}" class="btn btn-dark">Add New</a>
        </div>
    </div>
    <!--end breadcrumb-->





    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-around mb-3 text-center">
                <form method="GET" action="{{ route('expenses.summary') }}">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <h6>Start Date</h6>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <h6>End Date</h6>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                    <div class="row">
                        <h6>Total: {{formatCurrency($totalExpenses)}}</h6>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table id="expenses-data-table" class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr class="expense-row" data-category-id="{{ $expense->category_id }}">
                                    <td>{{ $expense->category->name ?? 'Uncategorized' }}</td>
                                    <td>{{ number_format($expense->total_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover" id="expense-items-table">
                        <thead>
                            <tr>
                                <th>Items</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<!--end row-->
</div>



<script>
    window.addEventListener('load', function() {


        $('.expense-row').css('cursor', 'pointer'); // Change cursor on hover

        $('.expense-row').on('click', function() {
            var categoryId = $(this).data('category-id');
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: '{{ route("expenses.items") }}',
                method: 'GET',
                data: {
                    category_id: categoryId,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(data) {
                    var tableBody = $('#expense-items-table tbody');
                    tableBody.empty(); // Clear the table

                    data.forEach(function(item) {
                        tableBody.append(`
                            <tr>
                                <td>${item.getItems}</td>
                                <td>${item.amount}</td>
                            </tr>
                        `);
                    });
                },
                error: function() {
                    alert('Failed to fetch expense items.');
                }
            });
        });
    });
</script>

@endsection