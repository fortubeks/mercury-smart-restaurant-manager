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
                    <li class="breadcrumb-item active" aria-current="page">Edit Expense</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('expenses.index') }}" class="btn btn-dark">View Expense(s)</a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <!--include flash message manually if you wish -->
                    <form action="{{ route('expenses.update', $expense->id) }}" method="post" autocomplete="off">
                        @csrf @method('put')
                        <div class="row no-gutters">
                            <div class="col-lg card-form__body card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="phone">Expense Category</label>
                                            <select id="category" class="form-select form-control" name="expense_category_id">
                                                @foreach (getModelList('expense-categories') as $expense_category)
                                                <option value="{{ $expense_category->id }}" {{($expense_category->id == $expense->expense_category_id ? 'selected' : '')}}>
                                                    {{ $expense_category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('alerts.error-feedback', ['field' => 'category'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="flatpickrSample01">Date</label>
                                            <input id="flatpickrSample01" type="date"
                                                class="form-control datepicker flatpickr-input active @error('expense_date') is-invalid @enderror"
                                                name="expense_date" data-toggle="flatpickr" value="{{ $expense->expense_date }}">
                                            @include('alerts.error-feedback', [
                                            'field' => 'expense_date',
                                            ])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="phone">Supplier</label>
                                            <select id="supplier" class="form-select form-control" name="supplier_id">
                                                <option value=""></option>
                                                @foreach (getModelList('suppliers') as $supplier)
                                                <option value="{{ $supplier->id }}" {{($supplier->id == $expense->supplier_id ? 'selected' : '')}}>{{ $supplier->name }}</option>
                                                @endforeach
                                            </select>
                                            @include('alerts.error-feedback', ['field' => 'supplier'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">File</label>
                                            <input id="" name="uploaded_file" type="file"
                                                class="form-control @error('uploaded_file') is-invalid @enderror"
                                                placeholder="uploaded_file">
                                            @include('alerts.error-feedback', [
                                            'field' => 'uploaded_file',
                                            ])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">Note</label>
                                            <input id="" name="note" type="text"
                                                class="form-control @error('note') is-invalid @enderror"
                                                placeholder="Note" value="{{ old('note') ?? $expense->note }}">
                                            @include('alerts.error-feedback', ['field' => 'note'])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div id="input-container" class="col-lg card-form__body card-body">
                                @foreach ($expense->items as $key => $item)
                                <div id="" class="row mb-3">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">Item/Description</label>
                                            <input id="{{ __('description' . $key) }}" name="description[]"
                                                type="text" readonly class="form-control" value="{{ $item->expenseItem->name }}">
                                            <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">Quantity</label>
                                            <input id="{{ __('qty' . $key) }}" name="qty[]" type="number"
                                                onkeyup="updateAmount(<?php echo $key; ?>)" inputmode="decimal"
                                                min="0" step="any"
                                                class="form-control @error('qty') is-invalid @enderror"
                                                placeholder="Qty" value="{{ old('qty') ?? $item->qty }}">
                                            @include('alerts.error-feedback', ['field' => 'qty'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">Rate</label>
                                            <input id="{{ __('rate' . $key) }}" name="rate[]" type="number"
                                                onkeyup="updateAmount(<?php echo $key; ?>)" inputmode="decimal"
                                                min="0" step="any"
                                                class="form-control @error('rate') is-invalid @enderror"
                                                placeholder="Rate" value="{{ old('rate') ?? $item->rate }}">
                                            @include('alerts.error-feedback', ['field' => 'rate'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">Amount</label>
                                            <input id="{{ __('amount' . $key) }}" name="amount[]" type="number"
                                                class="form-control money @error('amount') is-invalid @enderror total"
                                                placeholder="Amount"
                                                value="{{ old('amount') ?? $item->amount }}">
                                            @include('alerts.error-feedback', [
                                            'field' => 'amount',
                                            ])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="rooms">Unit Qty</label>
                                            <input id="{{ __('unit_qty' . $key) }}" name="unit_qty[]"
                                                type="number" min="0" step="any"
                                                class="form-control money @error('unit_qty') is-invalid @enderror"
                                                placeholder="Unit Qty"
                                                value="{{ old('unit_qty') ?? $item->unit_qty }}">
                                            @include('alerts.error-feedback', [
                                            'field' => 'unit_qty',
                                            ])
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                            <hr class="mb-2">

                        </div>
                        <div style="display: none;">
                            <div id="input-template" class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rooms">Item/Description</label>
                                        <input type="hidden" name="new_item[]">
                                        <input id="description_0" name="new_item_description[]" type="text"
                                            list="items_"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Name">
                                        @include('alerts.error-feedback', ['field' => 'description'])

                                        <datalist id="items_">
                                            @foreach (getModelList('expense-items') as $item_)
                                            <option value="{{ $item_->name }}">
                                                @endforeach
                                        </datalist>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rooms">Quantity</label>
                                        <input id="qty_0" name="new_item_qty[]" type="number"
                                            onkeyup="updateAmountNewInputs(0)" inputmode="decimal" min="0" step="any"
                                            class="form-control @error('qty') is-invalid @enderror" placeholder="Qty"
                                            value="{{ old('qty') }}">
                                        @include('alerts.error-feedback', ['field' => 'qty'])
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rooms">Rate</label>
                                        <input id="rate_0" name="new_item_rate[]" type="number"
                                            onkeyup="updateAmountNewInputs(0)" inputmode="decimal" min="0"
                                            step="any" class="form-control @error('rate') is-invalid @enderror"
                                            placeholder="Rate" value="{{ old('rate') }}">
                                        @include('alerts.error-feedback', ['field' => 'rate'])
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rooms">Amount</label>
                                        <input id="amount_0" name="new_item_amount[]" type="number"
                                            class="form-control money @error('amount') is-invalid @enderror total"
                                            placeholder="Amount" value="{{ old('amount') }}">
                                        @include('alerts.error-feedback', ['field' => 'amount'])
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rooms">Unit Qty</label>
                                        <input id="unitQty_0" name="new_item_unit_qty[]" type="number"
                                            class="form-control money @error('unit_qty') is-invalid @enderror"
                                            placeholder="Unit Qty" value="{{ old('unit_qty') }}">
                                        @include('alerts.error-feedback', ['field' => 'unit_qty'])
                                    </div>
                                </div>
                                <div class="col-12  d-flex justify-content-end mt-3">
                                    <a href="javascript:void(0);" class="ms-3 remove-button"><i class="bx bxs-trash"></i></a>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex" style="justify-content: space-between;">
                            <a class="btn btn-sm btn-primary" id="add-input">Add +</a>
                            <h5><span id="total_amount">Total: {{formatCurrency($expense->amount)}}</span></h5>
                        </div>

                        <hr class="mb-2">
                        <div class="row">
                            <div class="d-lg-flex align-items-center mb-4 gap-3">
                                <div class="position-relative">
                                    <h5>Payments</h5>
                                </div>
                                <div class="ms-auto"><a href="javascript:;" class="ms-3" onclick="setId(this)" data-amount="{{ $expense->amount }}" data-id="{{ $expense->id }}"
                                        title="Add payment" data-bs-toggle="modal" data-bs-target="#modal-payment" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add Payment</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="transactions-data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($expense->payments as $payment)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $payment->date_of_payment)->format('d-m-y') }}</td>
                                            <td>{{$payment->mode_of_payment}}</td>
                                            <td>{{formatCurrency($payment->amount)}}</td>
                                            <td><a href="javascript:void(0);" class="ms-3 delete-expense-payment" data-id="{{ $payment->id }}"
                                                    title="Delete payment" data-bs-toggle="modal" data-bs-target="#deletePaymentModal"><i class="bx bxs-trash"></i></a></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td>No payments yet</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="mb-2">

                        <div class="d-flex mt-5" style="justify-content: space-between;">
                            <a class="btn btn-danger " data-bs-toggle="modal" data-bs-target="#deleteExpenseModal"><span class="text">Delete</span><i class="bx bxs-trash"></i></a>
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteExpenseModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this expense?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" id="expense-form" action="{{ url('expenses/'.$expense->id) }}">
                        @csrf @method('delete')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
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
                            <div class="col-12 mb-2">
                                <div class="form-group">
                                    <label for="phone">Bank Account</label>
                                    <select class="form-select" name="bank_account_id">
                                        <option value="">--Select--</option>
                                        @foreach (getModelList('bank-accounts') as $bankAccount)
                                        <option value="{{$bankAccount->id}}">{{$bankAccount->account_name}} -
                                            {{$bankAccount->account_number}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
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
                        <input type="hidden" id="expense_id" value="{{$expense->id}}" name="expense_id">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div><!-- // END .modal-footer -->
                </form>
            </div> <!-- // END .modal-content -->
        </div> <!-- // END .modal-dialog -->
    </div> <!-- // END .modal -->

    <div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this payment?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" id="delete-payment-form" action="{{ url('expense-payments/') }}">
                        @csrf @method('delete')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            $('.btn-delete').click(function() {
                $('#form-delete').submit()
            });
            $(".delete-expense").click(function(event) {
                var expenseId = $(this).data('id');
                var currentUrl = "{{ url('expenses') }}";

                // Construct the new URL with appended bar expense ID
                var newUrl = currentUrl + "/" + expenseId;

                // Update the form action attribute with the new URL
                $("#expense-form").attr("action", newUrl);
            });
            $(".delete-expense-payment").click(function(event) {
                var paymentId = $(this).data('id');
                var currentUrl = "{{ url('expense-payments') }}";

                // Construct the new URL with appended bar expense ID
                var newUrl = currentUrl + "/" + paymentId;

                // Update the form action attribute with the new URL
                $("#delete-payment-form").attr("action", newUrl);
            });
            $("#add-input").click(function() {
                inputCounter++;

                // Clone the input template
                var newInput = $("#input-template").clone();

                // Update IDs and reset values
                newInput.find('[id]').each(function() {
                    var oldId = $(this).attr('id');
                    var newId = oldId.replace(/_0$/, '_' + inputCounter);
                    $(this).attr('id', newId);
                    $(this).val('');
                });

                // Attach event handlers for the new input fields
                newInput.find("input[type='number']").on('keyup', function() {
                    var index = $(this).attr('id').split('_')[1];
                    updateAmountNewInputs(index);
                });

                // Attach a click event to the remove button
                newInput.find(".remove-button").click(function() {
                    newInput.remove();
                });

                // Append the new input element to the container
                $("#input-container").append(newInput);

                // Trigger an initial update of the amount for the new input
                updateAmountNewInputs(inputCounter);
            });
            var category = $('#category').attr("data-category");
            $('#category option[value=' + category + ']').attr('selected', 'selected');
            var supplier = $('#supplier').attr("data-supplier");
            $('#supplier option[value=' + supplier + ']').attr('selected', 'selected');
        });
    </script>
    <script>
        let inputCounter = <?php echo count($expense->items) - 1 ?>;
        let total_amount = <?php echo $expense->amount ?>;

        function updateAmount(index) {
            var qty = parseFloat($("#qty" + index).val()) || 0;
            var rate = parseFloat($("#rate" + index).val()) || 0;
            var amount = qty * rate;
            $("#amount" + index).val(amount.toFixed(2)); // Format the amount as needed
            $("#unitQty" + index).val(qty); // set unit qty
            $("#received" + index).val(qty); // set received qty
            calculateTotalSum();
        }

        function updateAmountNewInputs(index) {
            var qty = parseFloat($("#qty_" + index).val()) || 0;
            var rate = parseFloat($("#rate_" + index).val()) || 0;
            var amount = qty * rate;
            $("#amount_" + index).val(amount.toFixed(2)); // Format the amount as needed
            $("#unitQty_" + index).val(qty); // set unit qty
            $("#received_" + index).val(qty); // set received qty
            calculateTotalSum();
        }

        function calculateTotalSum() {
            var sum = 0;
            $(".total").each(function() {
                var value = parseFloat($(this).val()) || 0;
                sum += value;
            });
            $('#total_amount').text(`Total:${formatCurrency(sum)}`);
        }
    </script>
    @endsection