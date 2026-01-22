@extends('rocker-theme.layouts.app')

<div class="page-wrapper">

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Expenses</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('expenses.summary') }}" class="btn btn-dark">View Summary</a>
                <a href="{{ route('expenses.create') }}" class="btn btn-dark">Add New</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-around mb-3 text-center">
                    <div class="col-12">
                        <h6> Unpaid Expenses (<a href="#">View Dashboard</a>) (<a href="{{ route('expenses.summary') }}">View Summary</a>)</h6>
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
                        <tbody>
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
                                <td>{{ $expense->category->name }}</td>
                                <td style="text-wrap: wrap;">{{ $expense->getItems() ?? '' }}</td>
                                <td>{{ $expense->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $expense->paymentStatus() ?? '' }}</td>
                                <td class="text-right">{{ formatCurrency($expense->amount) ?? '' }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a title="Show/Edit" href="{{ route('expenses.show', $expense) }}"><i class='bx bx-show'></i></a>
                                        <a href="javascript:void(0);" onclick="setId(this)" class="ms-3 add-payment" data-amount="{{ $expense->amount }}" data-id="{{ $expense->id }}"
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
                                    <h4>No Available Expenses</h4>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>

<div id="modal-payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-standard-title">Add Payment to Expense [<span id="expense_amount"></span>]</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- // END .modal-header -->
            <form action="{{url('expense-payments')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Amount') }}</label>
                            <input type="number" class="form-control" name="amount" value="0" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Mode of payment') }}</label>
                            <select name="mode_of_payment" class="form-select form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="phone">Bank Account</label>
                            <select class="form-select" name="bank_account_id">
                                <option value="">--Select--</option>
                                @foreach (getModelList('bank-accounts') as $bankAccount)
                                <option value="{{$bankAccount->id}}">{{ $bankAccount->account_name. "(".formatCurrency($bankAccount->balance).")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Date of payment') }}</label>
                            <input type="date" class="form-control datepicker flatpickr-input active" name="date_of_payment"
                                data-toggle="flatpickr" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Note') }}</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                    </div>
                </div><!-- // END .modal-body -->
                <div class="modal-footer">
                    <input type="hidden" id="expense_id" value="" name="expense_id">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div><!-- // END .modal-footer -->
            </form>
        </div> <!-- // END .modal-content -->
    </div> <!-- // END .modal-dialog -->
</div> <!-- // END .modal -->

<script>
    window.addEventListener('load', function() {
        var expenses_table = $('#expenses-data-table').DataTable({
            lengthChange: true,
            buttons: ['excel', 'pdf', 'print'],
            sort: false
        });

        expenses_table.buttons().container().appendTo('#expenses-data-table_wrapper .col-md-6:eq(0)');

        $('input').click(function() {
            this.select();
        });
    });

    function setId(item) {
        var id = item.dataset.id;
        var amount = item.dataset.amount;
        document.getElementById('expense_id').value = id;
        document.getElementById('expense_amount').innerHTML = amount;
    }
</script>

@include('rocker-theme.expenses.partials.add-payment')