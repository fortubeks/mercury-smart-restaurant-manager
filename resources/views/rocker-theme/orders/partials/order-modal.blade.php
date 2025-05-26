<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Proceed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ url('orders') }}">
                    @csrf
                    <!-- Step 1: Search or Create Customer -->
                    <div id="step-customer">
                        <input type="text" id="customer-search" class="form-control" placeholder="Search customer by name or phone">

                        <div id="customer-results" class="mt-3"></div>

                        <button class="btn btn-link" type="button" id="show-create-form">Customer not found? Create New</button>

                        <div id="create-customer-form" class="d-none mt-3">
                            <input type="text" id="new-customer-name" class="form-control mb-2" placeholder="Name">
                            <input type="text" id="new-customer-phone" class="form-control mb-2" placeholder="Phone">
                            <button class="btn btn-success" type="button" id="create-customer">Create and Continue</button>
                        </div>
                        <!-- At the end of #step-customer -->
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-primary mt-3" type="button" id="to-delivery">Next</button>
                        </div>
                    </div>

                    <!-- Step 2: Delivery Info -->
                    <div id="step-delivery" class="d-none">
                        <div class="tab-title">Delivery Information</div>
                        <div class="mb-3">
                            <label for="delivery-area" class="form-label">Delivery Area</label>
                            <select class="form-select" name="delivery_area_id" id="delivery-area" required>
                                <option value="">--Select Area--</option>
                                @foreach(getModelList('delivery-areas') as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="delivery-address" class="form-label">Delivery Address</label>
                            <input type="text" name="delivery_address" id="delivery-address" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="delivery-notes" class="form-label">Additional Notes</label>
                            <textarea name="delivery_notes" id="delivery-notes" class="form-control" rows="3"></textarea>
                        </div>
                        <!-- At the end of #step-delivery -->
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-secondary" type="button" id="back-to-customer">Back</button>
                            <button class="btn btn-primary" type="button" id="to-payment">Next</button>
                        </div>
                    </div>

                    <!-- Step 3: Payment -->
                    <div id="step-payment" class="d-none">
                        <div class="tab-title">Add Payment</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="inputPrice" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">--Select--</option>
                                    <option value="cash">Cash</option>
                                    <option value="pos">POS</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="wallet">Wallet</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="phone">Bank Account</label>
                                    <select class="form-select" name="bank_account_id">
                                        <option value="">--Select--</option>
                                        @foreach(getModelList('bank-accounts') as $bankAccount)
                                        <option value="{{$bankAccount->id}}">{{$bankAccount->account_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="inputCompareatprice" class="form-label">Amount Paid</label>
                                <input type="number" min="0" step="any" class="form-control" name="amount" id="amount-paid">
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-secondary" type="button" id="back-to-delivery">Back</button>
                                <button class="btn btn-primary" type="button" id="to-summary">Review Summary</button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Summary -->
                    <div id="step-summary" class="d-none">
                        <div class="tab-title">Summary</div>
                        <ul class="list-group mb-3">
                            <li class="list-group-item"><strong>Customer:</strong> <span id="summary-customer"></span></li>
                            <li class="list-group-item"><strong>Delivery Area:</strong> <span id="summary-area"></span></li>
                            <li class="list-group-item"><strong>Address:</strong> <span id="summary-address"></span></li>
                            <li class="list-group-item"><strong>Notes:</strong> <span id="summary-notes"></span></li>
                            <li class="list-group-item"><strong>Amount:</strong> ₦<span id="summary-amount"></span></li>
                            <li class="list-group-item"><strong>Payment Mode:</strong> <span id="summary-mode"></span></li>
                        </ul>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-secondary" type="button" id="back-to-payment">Back</button>
                            <input type="hidden" id="selected-customer-id" name="customer_id">
                            <input type="hidden" name="delivery_area_id" id="form-delivery-area">
                            <input type="hidden" name="delivery_address" id="form-delivery-address">
                            <input type="hidden" name="delivery_notes" id="form-delivery-notes">
                            <input type="hidden" name="order_cart_id" class="orderCartId">
                            <button type="submit" class="btn btn-success">Submit Order</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go back</button>
            </div> -->
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        // Live search
        $('#customer-search').on('input', function() {
            const query = $(this).val();
            if (query.length < 3) return;

            $.get('{{route("search.customers")}}', {
                q: query
            }, function(data) {
                let html = '';
                if (data.length) {
                    data.forEach(customer => {
                        html += `<div class="customer-option" data-id="${customer.id}">${customer.first_name}, ${customer.last_name} (${customer.phone})</div>`;
                    });
                } else {
                    html = '<p>No customer found.</p>';
                }
                $('#customer-results').html(html);
            });
        });

        // Select customer
        $(document).on('click', '.customer-option', function() {
            const customerId = $(this).data('id');
            $('#selected-customer-id').val(customerId);
            $('#step-customer').addClass('d-none');
            $('#step-delivery').removeClass('d-none');
        });

        // Show create form
        $('#show-create-form').on('click', function() {
            $('#create-customer-form').removeClass('d-none');
        });

        // Create customer
        $('#create-customer').on('click', function() {
            const name = $('#new-customer-name').val();
            const phone = $('#new-customer-phone').val();
            $.post('{{route("customers.store")}}', {
                'first_name': name,
                'phone': phone,
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#selected-customer-id').val(data.id);
                $('#step-customer').addClass('d-none');
                $('#step-delivery').removeClass('d-none');
            });
        });

        // Proceed from Delivery to Payment
        $('#continue-to-payment').on('click', function() {
            if ($('#delivery-area').val() && $('#delivery-address').val()) {
                $('#step-delivery').addClass('d-none');
                $('#step-payment').removeClass('d-none');
            } else {
                alert('Please fill out all delivery fields.');
            }
        });

        // Step transitions
        $('#to-delivery').on('click', () => {
            if (!$('#selected-customer-id').val()) {
                alert('Select Customer first.');
                return;
            }
            $('#step-customer').addClass('d-none');
            $('#step-delivery').removeClass('d-none');
        });

        $('#back-to-customer').on('click', () => {
            $('#step-delivery').addClass('d-none');
            $('#step-customer').removeClass('d-none');
        });

        $('#to-payment').on('click', () => {
            if (!$('#delivery-area').val() || !$('#delivery-address').val()) {
                alert('Fill delivery area and address.');
                return;
            }
            $('#step-delivery').addClass('d-none');
            $('#step-payment').removeClass('d-none');
        });

        $('#back-to-delivery').on('click', () => {
            $('#step-payment').addClass('d-none');
            $('#step-delivery').removeClass('d-none');
        });

        $('#to-summary').on('click', () => {
            if (!$('#amount-paid').val() || !$('[name="payment_method"]').val()) {
                alert('Fill amount and payment method.');
                return;
            }

            // Populate summary
            const customer = $('.customer-option[data-id="' + $('#selected-customer-id').val() + '"]').text();
            $('#summary-customer').text(customer);
            $('#summary-area').text($('#delivery-area option:selected').text());
            $('#summary-address').text($('#delivery-address').val());
            $('#summary-notes').text($('#delivery-notes').val());
            $('#summary-amount').text($('#amount-paid').val());
            $('#summary-mode').text($('[name="payment_method"] option:selected').text());

            $('#step-payment').addClass('d-none');
            $('#step-summary').removeClass('d-none');
        });

        $('#back-to-payment').on('click', () => {
            $('#step-summary').addClass('d-none');
            $('#step-payment').removeClass('d-none');
        });

        // On final submit
        $('form').on('submit', function() {
            $('#form-delivery-area').val($('#delivery-area').val());
            $('#form-delivery-address').val($('#delivery-address').val());
            $('#form-delivery-notes').val($('#delivery-notes').val());
        });
    });
</script>