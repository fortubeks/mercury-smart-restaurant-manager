@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <h5>Expenses</h5>
            </div>
            <div class="ms-auto"><a href="{{ route('expenses.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>New PuExpenserchase</a></div>
        </div>
        <!--end breadcrumb-->

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Today</p>
                                <h5 class="my-1">{{formatCurrency($metrics['today'])}}</h5>
                                <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i></p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">This Week</p>
                                <h5 class="my-1">{{formatCurrency($metrics['this_week'])}}</h5>
                                <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i></p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">This Month</p>
                                <h5 class="my-1">{{formatCurrency($metrics['this_month'])}}</h5>
                                <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i></p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary"> Weekly Average</p>
                                <h5 class="my-1">{{formatCurrency($metrics['avg_weekly'])}}</h5>
                                <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i></p>
                            </div>
                            <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <label class="form-label">This Month - Top 5</label>
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Count</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($metrics['topMonthlyExpenseItems'] as $key => $expense_item)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td>{{$expense_item->expenseItem->name}}</td>
                                    <td>{{$expense_item->total_count}}</td>
                                    <td>{{formatCurrency($expense_item->total_amount)}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <label class="form-label">This Year - Top 5</label>
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Count</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($metrics['topYearlyExpenseItems'] as $key => $expense_item)
                                <tr>
                                    <th scope="row">{{$key+1}}</th>
                                    <td>{{$expense_item->expenseItem->name}}</td>
                                    <td>{{$expense_item->total_count}}</td>
                                    <td>{{formatCurrency($expense_item->total_amount)}}</td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-around mb-3 text-center">
                    <div class="col-12">
                        <h6> Recent Expenses (<a href="{{ route('expenses.all') }}">View All</a>) (<a href="{{ route('expenses.summary') }}">View Summary</a>)
                            (<a href="{{ route('expenses.unpaid') }}">View Unpaid</a>)</h6>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="expenses-data-table" class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Supplier</th>
                                <th>Payment Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($expenses->count())
                        <tbody style="text-wrap: wrap;">
                            @foreach ($expenses as $expense)
                            @php
                            $expenseStatus = $expense->status;
                            $expenseStatusColor = '';
                            if ($expenseStatus == 'Recieved') {
                            $expenseStatusColor = 'text-success';
                            }
                            if ($expenseStatus == 'Partial') {
                            $expenseStatusColor = 'text-danger';
                            }
                            if ($expenseStatus == 'Ordered') {
                            $expenseStatusColor = 'text-primary';
                            }
                            if ($expenseStatus == 'Pending') {
                            $expenseStatusColor = 'text-warning';
                            }
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $expense->expense_date)->format('jS, M Y') }}</td>
                                <td>{{ $expense->expenseCategory->name }}</td>
                                <td>{{ $expense->getItems() ?? '' }}</td>
                                <td>{{ $expense->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $expense->paymentStatus() ?? '' }}</td>
                                <td class="text-right">{{ formatCurrency($expense->amount) ?? '' }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a title="Show/Edit" href="{{ route('expenses.show', $expense) }}"><i class='bx bx-show'></i></a>
                                        <a href="javascript:void(0);" class="ms-3 add-payment" data-amount="{{ $expense->amount }}" data-id="{{ $expense->id }}"
                                            title="Add payment" data-bs-toggle="modal" data-bs-target="#modal-payment"><i class="fadeIn animated bx bx-money"></i></a>
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <h5>No Available Expenses</h5>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('rocker-theme.expenses.partials.add-payment')
</div>


<script>
    window.addEventListener('load', function() {
        var expenses_table = $('#expenses-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print'],
            order: false
        });

        expenses_table.buttons().container().appendTo('#expenses-data-table_wrapper .col-md-6:eq(0)');

        $('input').click(function() {
            this.select();
        });
        $(".add-payment").click(function(event) {
            var id = $(this).data('id');
            var amount = $(this).data('amount');
            document.getElementById('expense_id').value = id;
            document.getElementById('expense_amount').innerHTML = amount;
        });
    });
</script>