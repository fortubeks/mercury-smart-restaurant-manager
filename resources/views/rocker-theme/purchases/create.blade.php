@extends('rocker-theme.layouts.app')
<style>
    .remove-button {
        font-size: 24px;
        color: red
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <!--include flash message manually if you wish -->
                        <form action="{{ route('purchases.store') }}" method="POST">
                            @csrf

                            <div class="row mx-auto">
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
                                    <label for="name" class="form-label">Category</label>
                                    <select id="category" class="form-select form-control" required name="category_id">
                                        <option value="">--Select Category--</option>
                                        @foreach (getModelList('purchase-categories') as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
                                    <label for="flatpickrSample01" class="form-label ">Date</label>
                                    <input id="flatpickrSample01" type="date"
                                        class="form-control datepicker flatpickr-input active" name="purchase_date"
                                        data-toggle="flatpickr" value="{{ now()->format('Y-m-d') }}">

                                    @error('purchase_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 pb-3">
                                    <label for="address" class="form-label">Supplier</label> <span class="ll-5"> <a
                                            href="{{ route('suppliers.create') }}" class="">Add</a>
                                    </span>
                                    <select class="form-select form-control" name="supplier_id"
                                        id="">
                                        <option value="">Select Supplier</option>
                                        @foreach (getModelList('suppliers') as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->name }}
                                        </option>
                                        @endforeach

                                    </select>
                                    @error('supplier')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 pb-3">
                                    <label for="amount" class="form-label">Receipt/Invoice</label>
                                    <input id="" name="uploaded_file" type="file"
                                        class="form-control @error('uploaded_file') is-invalid @enderror"
                                        placeholder="uploaded_file">
                                    @error('uploaded_file')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 pb-3">
                                    <label for="input4" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control form-select">
                                        @foreach (getStatusOptions() as $key => $status)
                                        <option value="{{ $key }}">
                                            {{ $status }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 pb-3">
                                    <label for="note" class="form-label">Note</label>
                                    <textarea id="" name="note" type="text" class="form-control @error('note') is-invalid @enderror"
                                        placeholder="Note">{{ old('note') }}</textarea>
                                    @error('note')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            <div class="row no-gutters">
                                <div id="input-container" class="card-form__body card-body">
                                    <table class="table">
                                        <tbody id="input-template">
                                            <tr class="input-row">
                                                <td>
                                                    <label class="form-label">Item/Description</label>
                                                    <select name="store_items[]" class="form-select selectpicker item-select">
                                                        <option value="">--Select Item--</option>
                                                        @foreach (getModelList('store-items') as $item)
                                                        <option value="{{ $item->id }}" data-value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <label class="form-label">Quantity</label>
                                                    <input name="qty[]" type="number" inputmode="decimal" min="0"
                                                        step="any" class="form-control qty-input" placeholder="Qty">
                                                </td>
                                                <td>
                                                    <label class="form-label">Received</label>
                                                    <input name="received[]" inputmode="decimal" min="0"
                                                        step="any" type="number" class="form-control received-input" placeholder="Received">
                                                </td>
                                                <td>
                                                    <label class="form-label">Rate</label>
                                                    <input name="rate[]" inputmode="decimal" min="0"
                                                        step="any" type="number" class="form-control rate-input" placeholder="Rate">
                                                </td>
                                                <td>
                                                    <label class="form-label">Amount</label>
                                                    <input name="amount[]" type="number" class="form-control amount-input" placeholder="Amount" readonly>
                                                </td>
                                                <td>
                                                    <label class="form-label">Unit Quantity</label>
                                                    <input name="unit_qty[]" inputmode="decimal" min="0"
                                                        step="any" type="number" class="form-control unitQty-input" placeholder="Unit Qty">
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end mt-2">
                                                        <i class="bx bxs-trash remove-button text-danger" style="cursor: pointer;"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <button type="button" class="btn btn-sm btn-primary" id="add-input">Add +</button>
                                <h5><span id="total_amount">Total: {{ formatCurrency(0) }}</span></h5>
                            </div>

                            <hr class="mb-2">
                            <div class="row">
                                <div class="col-3">
                                    <label>{{ __('Add Payment') }}</label>
                                    <input type="number" class="form-control" name="payment_amount" placeholder="Amount">
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

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let inputCounter = 0;
    window.addEventListener('load', function() {
        $("#add-input").click(function() {
            inputCounter++;

            // Clone the template row and reset values
            let newRow = $("#input-template .input-row:first").clone();
            newRow.find("input, select").each(function() {
                $(this).val('');
            });

            // Append new row
            newRow.appendTo("#input-container table tbody");

            // Rebind events
            bindEvents();
        });

        function bindEvents() {
            $(".qty-input, .rate-input").off('input').on('input', function() {
                let row = $(this).closest('.input-row');
                updateAmount(row);
            });

            $(".remove-button").off('click').on('click', function() {
                if ($(".input-row").length > 1) {
                    $(this).closest('.input-row').remove();
                    calculateTotalSum();
                }
            });
        }

        function updateAmount(row) {
            let qty = parseFloat(row.find(".qty-input").val()) || 0;
            let rate = parseFloat(row.find(".rate-input").val()) || 0;
            let amount = qty * rate;

            row.find(".amount-input").val(amount.toFixed(2));
            row.find(".unitQty-input").val(qty);
            row.find(".received-input").val(qty);

            calculateTotalSum();
        }

        function calculateTotalSum() {
            let sum = 0;
            $(".amount-input").each(function() {
                sum += parseFloat($(this).val()) || 0;
            });
            $('#total_amount').text(`Total: ${formatCurrency(sum)}`);
        }

        // Bind events on initial elements
        bindEvents();
    });
</script>