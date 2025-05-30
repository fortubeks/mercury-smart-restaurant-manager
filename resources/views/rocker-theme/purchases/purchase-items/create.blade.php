@extends('dashboard.layouts.app')

<style>
    .user-photo {
        width: 40px;
        height: auto;
    }

    .remove-button {
        font-size: 24px;
        color: red
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
                        <li class="breadcrumb-item active" aria-current="page">Create a Purchase Store Item</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('purchase-store-items.store') }}" class="btn btn-dark">View Purchase Store Item</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <!--include flash message manually if you wish -->
                        <form action="{{ route('purchase-store-items.store') }}" method="POST">
                            @csrf
                            <div id="input-container" class="card-form__body card-body">
                                <table class="table">
                                    <tbody id="input-template">
                                        <div class="row mx-auto">
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
                                                <label for="name" class="form-label">Store Item</label>
                                                <select id="store-item_id" class="form-select form-control"
                                                    name="store-item_id">
                                                    <option value="">Select Store Item</option>
                                                    @foreach (getModelList('store-items') as $store_item)
                                                        <option value="{{ $store_item->id }}">
                                                            {{ $store_item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('store-item_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-6 pb-3">
                                                <label for="name" class="form-label">Purchase</label>
                                                <select id="store-item_id" class="form-select form-control"
                                                    name="store-item_id">
                                                    <option value="">Select Store Item</option>
                                                    @foreach (getModelList('purchases') as $store_item)
                                                        <option value="{{ $store_item->id }}">
                                                            {{ $store_item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('store-item_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <tr>

                                                <td>
                                                    <label for="note" class="form-label">Quantity</label>
                                                    <input id="qty_0" name="qty[]" type="number"
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                        step="any"
                                                        class="form-control @error('qty') is-invalid @enderror"
                                                        placeholder="Qty" value="{{ old('qty') }}">
                                                    @error('qty')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" class="form-label">Received</label>
                                                    <input id="received_0" name="received[]" type="number"
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                        step="any"
                                                        class="form-control @error('received') is-invalid @enderror"
                                                        placeholder="Received" value="{{ old('received') }}">
                                                    @error('received')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" class="form-label">Rate</label>
                                                    <input id="rate_0" name="rate[]" type="number"
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                        step="any"
                                                        class="form-control @error('rate') is-invalid @enderror"
                                                        placeholder="Rate" value="{{ old('rate') }}">
                                                    @error('rate')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" class="form-label">Tax Rate</label>
                                                    <input id="tax_rate_0" name="tax_rate[]" type="number"
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                        step="any"
                                                        class="form-control @error('rate') is-invalid @enderror"
                                                        placeholder="Tax Rate" value="{{ old('tax_rate') }}">
                                                    @error('tax_rate')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" class="form-label">Tax Amount</label>
                                                    <input id="tax_amount_0" name="tax_amount[]" type="number"
                                                        onkeyup="updateAmount(0)" inputmode="decimal" min="0"
                                                        step="any"
                                                        class="form-control @error('tax_amount') is-invalid @enderror"
                                                        placeholder="Tax Amount" value="{{ old('tax_amount') }}">
                                                    @error('tax_amount')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" readonly class="form-label">Amount</label>
                                                    <input id="amount_0" name="amount[]" type="number"
                                                        class="form-control money @error('amount') is-invalid @enderror"
                                                        placeholder="Amount" value="{{ old('amount') }}">
                                                    @error('amount')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" class="form-label">Unit Quantity</label>
                                                    <input id="unitQty_0" name="unit_qty[]" type="number"
                                                        class="form-control money @error('unit_qty') is-invalid @enderror"
                                                        placeholder="Unit Qty" value="{{ old('unit_qty') }}">
                                                    @error('unit_qty')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <label for="note" class="form-label">Total Amount</label>
                                                    <input id="total_amount_0" readonly name="total_amount[]" type="number"
                                                        class="form-control money @error('total_amount') is-invalid @enderror"
                                                        placeholder="Total Amount" value="{{ old('total_amount') }}">
                                                    @error('total_amount')
                                                        <span class="invalid-feedback"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-end mt-2">
                                                        <i class="bx bxs-trash remove-button" id="remove-button"></i>
                                                    </div>
                                                </td>
                                            </tr>
                                    </tbody>

                                </table>

                            </div>



                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-primary" id="add-input">Add +</button>
                            </div>
                            <div class=" col-12 d-flex justify-content-end mt-5">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ env('APP_URL') }}/assets/js/dselect.js"></script>
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
                    updateAmount(index);
                });

                // Attach a click event to the remove button
                newInput.find(".remove-button").click(function() {
                    newInput.remove();
                });

                // Append the new input element to the container
                $("#input-container").append(newInput);



                // Trigger an initial update of the amount for the new input
                updateAmount(inputCounter);
            });
        });
    </script>
    <script>
        let inputCounter = 0;

        function updateAmount(index) {
            var qty = parseFloat($("#qty_" + index).val()) || 0;
            var rate = parseFloat($("#rate_" + index).val()) || 0;
            var amount = qty * rate;
            var taxRate = parseFloat($("#tax_rate_" + index).val()) || 0;
            var taxAmount = (amount * taxRate) / 100;
            var totalAmount = amount + taxAmount;
            $("#amount_" + index).val(amount.toFixed(2));
            $("#tax_amount_" + index).val(taxAmount.toFixed(2));
            $("#total_amount_" + index).val(totalAmount.toFixed(2));
            $("#unitQty_" + index).val(qty); // set unit qty
        }
    </script>
    <!--end row-->
@endsection
