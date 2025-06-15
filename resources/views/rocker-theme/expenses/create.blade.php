@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->
        <style>
            .remove-button {
                font-size: 24px;
                cursor: pointer;
            }
        </style>
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <!--include flash message manually if you wish -->
                        <form action="{{ route('expenses.store') }}" method="post" autocomplete="off">
                            @csrf
                            <div class="row no-gutters">
                                <div class="col-lg card-form__body card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="phone">Expense Category</label>
                                                <select id="category" class="form-select form-control" name="category_id">
                                                    @foreach (getModelList('expense-categories') as $expense_category)
                                                    <option value="{{ $expense_category->id }}">
                                                        {{ $expense_category->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'category'])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="flatpickrSample01">Date</label>
                                                <input id="flatpickrSample01" type="date" required
                                                    class="form-control datepicker flatpickr-input active @error('expense_date') is-invalid @enderror"
                                                    name="expense_date" data-toggle="flatpickr" value="{{now()->format('Y-m-d')}}">
                                                @include('rocker-theme.alerts.error-feedback', [
                                                'field' => 'expense_date',
                                                ])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="phone">Supplier <a
                                                        href="{{ url('suppliers/create') }}">(Add)</a></label>
                                                <select id="supplier_id" class="form-select form-control"
                                                    name="supplier_id">
                                                    <option value=""></option>
                                                    @foreach (getModelList('suppliers') as $supplier)
                                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'supplier'])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="rooms">File</label>
                                                <input id="" name="uploaded_file" type="file"
                                                    class="form-control @error('uploaded_file') is-invalid @enderror"
                                                    placeholder="uploaded_file">
                                                @include('rocker-theme.alerts.error-feedback', [
                                                'field' => 'uploaded_file',
                                                ])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="rooms">Note</label>
                                                <input id="" name="note" type="text"
                                                    class="form-control @error('note') is-invalid @enderror"
                                                    placeholder="Note">
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'note'])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div id="input-container" class="card-form__body card-body">
                                    <table class="table">
                                        <tbody id="" class="mb-3">
                                            <tr>
                                                <td>
                                                    <label for="rooms">Item/Description</label>
                                                    <input id="description_0" required name="description[]" type="text"
                                                        list="items" class="form-control" placeholder="Name" list="items">

                                                    <datalist id="items">
                                                        @foreach (getModelList('expense-items') as $item)
                                                        <option value="{{ $item->name }}">
                                                            @endforeach
                                                    </datalist>
                                                </td>
                                                <td>
                                                    <label for="rooms">Quantity</label>
                                                    <input id="qty_0" name="qty[]" type="number" required
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                        step="any" class="form-control" placeholder="Qty">
                                                </td>
                                                <td>
                                                    <label for="rooms">Rate</label>
                                                    <input id="rate_0" name="rate[]" type="number"
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0" step="any"
                                                        class="form-control" placeholder="Rate">
                                                </td>
                                                <td>
                                                    <label for="rooms">Amount</label>
                                                    <input id="amount_0" name="amount[]" readonly type="number"
                                                        class="form-control money total" placeholder="Amount">
                                                </td>
                                                <td>
                                                    <label for="rooms">Unit Qty</label>
                                                    <input id="unitQty_0" name="unit_qty[]" type="number"
                                                        inputmode="decimal" min="0" step="any" class="form-control money" placeholder="Unit Qty">
                                                </td>
                                                <td>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-none">
                                    <div id="input-container" class="card-form__body card-body">
                                        <table id="input-template" class="table">
                                            <tbody class="mb-3">
                                                <tr>
                                                    <td>
                                                        <label for="rooms">Item/Description</label>
                                                        <input id="description_0" name="description[]" type="text"
                                                            list="items" class="form-control" placeholder="Name" list="items">

                                                        <datalist id="items">
                                                            @foreach (getModelList('expense-items') as $item)
                                                            <option value="{{ $item->name }}">
                                                                @endforeach
                                                        </datalist>
                                                    </td>
                                                    <td>
                                                        <label for="rooms">Quantity</label>
                                                        <input id="qty_0" name="qty[]" type="number"
                                                            onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                            step="any" class="form-control" placeholder="Qty">
                                                    </td>
                                                    <td>
                                                        <label for="rooms">Rate</label>
                                                        <input id="rate_0" name="rate[]" type="number"
                                                            onkeyup="updateAmount(0)" inputmode="decimal" min="0" step="any"
                                                            class="form-control" placeholder="Rate">
                                                    </td>
                                                    <td>
                                                        <label for="rooms">Amount</label>
                                                        <input id="amount_0" name="amount[]" readonly type="number"
                                                            class="form-control money total" placeholder="Amount">
                                                    </td>
                                                    <td>
                                                        <label for="rooms">Unit Qty</label>
                                                        <input id="unitQty_0" name="unit_qty[]" type="number"
                                                            inputmode="decimal" min="0" step="any" class="form-control money" placeholder="Unit Qty">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end mt-4">
                                                            <i class="bx bxs-trash remove-button" id="remove-button"></i>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex" style="justify-content: space-between;">
                                <a class="btn btn-sm btn-primary" id="add-input">Add +</a>
                                <h5><span id="total_amount">Total: {{formatCurrency(0)}}</span></h5>
                            </div>

                            <hr class="mb-2">
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ __('Add Payment') }}</label>
                                    <input type="number" class="form-control" id="payment_amount" name="payment_amount" placeholder="Amount">
                                </div>
                                <div class="col-3">
                                    <label>{{ __('Mode of payment') }}</label>
                                    <select name="payment_method" class="form-select form-control">
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Transfer</option>
                                        <option value="pos">POS</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label>{{ __('Date of payment') }}</label>
                                    <input type="date" class="form-control datepicker flatpickr-input active" name="date_of_payment"
                                        data-toggle="flatpickr" value="{{ now()->format('Y-m-d') }}">
                                </div>
                                <div class="col-3">
                                    <label for="phone">Bank Account</label>
                                    <select class="form-select" name="bank_account_id">
                                        <option value="">--Select--</option>
                                        @foreach (getModelList('bank-accounts') as $bankAccount)
                                        <option value="{{$bankAccount->id}}">{{ $bankAccount->account_name. "(".formatCurrency($bankAccount->balance).")" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-12 d-flex justify-content-end mt-3 mb-5">
                                <button class="btn btn-primary">Save</button>
                            </div>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        let inputCounter = 0;

        $("#add-input").click(function() {
            inputCounter++;

            // Clone the input template
            var newInput = $("#input-template").clone();
            newInput.removeAttr('id');
            newInput.removeClass('d-none');

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
                updateAmount(index);
            });

            // Attach a click event to the remove button
            newInput.find(".remove-button").click(function() {
                $(this).closest('tr').remove();
            });

            // Append the new input element to the container
            $("#input-container").append(newInput);

            // Trigger an initial update of the amount for the new input
            updateAmount(inputCounter);
        });
    });

    function updateAmount(index) {
        var qty = parseFloat($("#qty_" + index).val()) || 0;
        var rate = parseFloat($("#rate_" + index).val()) || 0;
        var amount = qty * rate;
        $("#amount_" + index).val(amount.toFixed(2)); // Format the amount as needed
        $("#unitQty_" + index).val(qty); // set unit qty
        calculateTotalSum();
    }

    function calculateTotalSum() {
        var sum = 0;
        $(".total").each(function() {
            var value = parseFloat($(this).val()) || 0;
            sum += value;
        });
        $('#total_amount').text(`Total:${formatCurrency(sum)}`);
        // $('#payment_amount').attr('max', sum);
        // $('#payment_amount').val(sum);
    }
</script>