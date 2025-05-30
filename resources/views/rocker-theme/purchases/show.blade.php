@extends('rocker-theme.layouts.app')
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
                        <form action="{{ url('purchases/'.$purchase->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row mx-auto">
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
                                    <label for="name" class="form-label">Category</label>
                                    <select id="category" class="form-select form-control" name="purchase_category_id">
                                        <option value="">Select Category</option>
                                        @foreach (getModelList('store-item-categories') as $category)
                                        <option value="{{ $category->id }}" {{($category->id == $purchase->purchase_category_id ? 'selected' : '')}}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('purchase_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
                                    <label class="form-label">Date</label>
                                    <input id="flatpickrSample01" type="text" class="form-control @error('purchase_date') is-invalid @enderror" name="purchase_date" data-toggle="flatpickr" value="{{$purchase->purchase_date}}">
                                    @include('rocker-theme.alerts.error-feedback', ['field' => 'purchase_date'])
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
                                    <select id="supplier" class="form-select form-control" name="supplier_id">
                                        <option value="">--Select--</option>
                                        @foreach(getModelList('suppliers') as $supplier)
                                        <option value="{{$supplier->id}}" {{($supplier->id == $purchase->supplier_id ? 'selected' : '')}}>{{$supplier->name}}</option>
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
                                        <option value="{{ $key }}" {{($key == $purchase->status ? 'selected' : '')}}>
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
                            <hr class="mb-2">

                            <div style="display: none;">
                                <div id="input-template" class="row mb-3">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="note" class="form-label">Item/Description</label>

                                            <select id="description_0" name="new_item[]" class="form-select selectpicker">
                                                <option value="">--Select Item--</option>
                                                @foreach (getModelList('store-items') as $item)
                                                <option value="{{ $item->id }}" data-value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label">Quantity</label>
                                            <input id="qty_0" name="new_item_qty[]" type="number" onkeyup="updateAmountNewInputs(0)" inputmode="decimal" min="0" step="any" class="form-control qty" placeholder="Qty" value="{{ old('qty') }}">
                                            @include('rocker-theme.alerts.error-feedback', ['field' => 'qty'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label">Received</label>
                                            <input id="received_0" name="new_item_received[]" type="number" onkeyup="updateAmountNewInputs(0)" inputmode="decimal" min="0" step="any" class="form-control " placeholder="Received" value="{{ old('received') }}">
                                            @include('rocker-theme.alerts.error-feedback', ['field' => 'received'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label">Rate</label>
                                            <input id="rate_0" name="new_item_rate[]" type="number" onkeyup="updateAmountNewInputs(0)" inputmode="decimal" min="0" step="any" class="form-control rate" placeholder="Rate" value="{{ old('rate') }}">
                                            @include('rocker-theme.alerts.error-feedback', ['field' => 'rate'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label">Amount</label>
                                            <input id="amount_0" name="new_item_amount[]" type="number" class="form-control money @error('amount') is-invalid @enderror total" placeholder="Amount" value="{{ old('amount') }}">
                                            @include('rocker-theme.alerts.error-feedback', ['field' => 'amount'])
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label class="form-label">Unit Qty</label>
                                            <input id="unitQty_0" name="new_item_unit_qty[]" type="number" class="form-control money @error('unit_qty') is-invalid @enderror" placeholder="Unit Qty" value="{{ old('unit_qty') }}">
                                            @include('rocker-theme.alerts.error-feedback', ['field' => 'unit_qty'])
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <a href="javascript:void(0);" class="ms-3 remove-button"><i class="bx bxs-trash"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-gutters">
                                <div id="input-container" class="col-lg card-form__body card-body">
                                    @foreach($purchase->items as $key => $item)
                                    <div id="" class="row mb-3">
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Item/Description</label>
                                                <input id="{{ __('description'.$key) }}" name="store_item[]" type="text" readonly class="form-control" value="{{ $item->storeItem->name }}">
                                                <input type="hidden" name="purchase_store_item_id[]" value="{{$item->id}}">

                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Quantity</label>
                                                <input id="{{ __('qty'.$key) }}" name="qty[]" type="number" onkeyup="updateAmount(<?php echo $key ?>)" inputmode="decimal" min="0" step="any" class="form-control qty" placeholder="Qty" value="{{ $item->qty }}">
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'qty'])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Received</label>
                                                <input id="{{ __('received'.$key) }}" name="received[]" type="number" onkeyup="updateAmount(<?php echo $key ?>)" inputmode="decimal" min="0" step="any" class="form-control qty" placeholder="Qty" value="{{ $item->received }}">
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'received'])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Rate</label>
                                                <input id="{{ __('rate'.$key) }}" name="rate[]" type="number" onkeyup="updateAmount(<?php echo $key ?>)" inputmode="decimal" min="0" step="any" class="form-control rate" placeholder="Rate" value="{{ $item->rate }}">
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'rate'])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Amount</label>
                                                <input id="{{ __('amount'.$key) }}" name="amount[]" type="number" class="form-control money total" placeholder="Amount" value="{{  $item->amount }}">
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'amount'])
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Unit Qty</label>
                                                <input id="{{ __('unitQty'.$key) }}" name="unit_qty[]" type="number" class="form-control money @error('unit_qty') is-invalid @enderror" placeholder="Unit Qty" value="{{ $item->unit_qty }}">
                                                @include('rocker-theme.alerts.error-feedback', ['field' => 'unit_qty'])
                                            </div>
                                        </div>

                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="d-flex" style="justify-content: space-between;">
                                <a class="btn btn-sm btn-primary" id="add-input">Add +</a>
                                <h5><span id="total_amount">Total: {{formatCurrency($purchase->total_amount) }}</span></h5>
                            </div>

                            <hr class="mb-2">
                            <div class="row">
                                <div class="d-lg-flex align-items-center mb-4 gap-3">
                                    <div class="position-relative">
                                        <h5>Payments</h5>
                                    </div>
                                    <div class="ms-auto"><a href="javascript:;" class="ms-3 add-payment" data-amount="{{ $purchase->total_amount }}" data-id="{{ $purchase->id }}"
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
                                            @forelse($purchase->outgoingPayments as $payment)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $payment->date_of_payment)->format('d-m-y') }}</td>
                                                <td>{{$payment->mode_of_payment}}</td>
                                                <td>{{formatCurrency($payment->amount) }}</td>
                                                <td><a href="javascript:void(0);" class="ms-3 delete-resource" data-resource-id="{{$purchase->id}}" data-resource-url="{{url('purchase-payments')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"
                                                        title="Delete payment"><i class=" bx bxs-trash"></i></a></td>
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
                                <a class="btn btn-danger delete-resource" data-resource-id="{{$purchase->id}}" data-resource-url="{{url('purchases')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><span class="text">Delete</span><i class="bx bxs-trash"></i></a>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('rocker-theme.layouts.partials.delete-modal')
@include('rocker-theme.purchases.partials.payment-modal')

<script>
    window.addEventListener('load', function() {

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
    });
</script>
<script>
    let inputCounter = <?php echo count($purchase->items) - 1 ?>;
    let total_amount = <?php echo $purchase->total_amount ?>;

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