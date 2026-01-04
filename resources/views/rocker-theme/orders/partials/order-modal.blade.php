<style>
    .customer-option {
        cursor: pointer;
    }
</style>
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
                        <p><b>{{ $cartData['order_info']['customer_name'] ?? '' }}</b></p>
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
                            <input class="form-control" list="delivery-areas" name="delivery_area_name" id="delivery-area"
                                value="{{ $cartData['order_info']['selected_delivery_area_name'] ?? '' }}">

                            <datalist id="delivery-areas">
                                @foreach(getModelList('delivery-areas') as $area)
                                <option value="{{ $area->name }}">
                                    @endforeach
                            </datalist>
                        </div>
                        <div class="mb-3">
                            <label for="delivery-address" class="form-label">Delivery Address</label>
                            <input type="text" name="delivery_address" id="delivery-address" class="form-control" value="{{ $cartData['order_info']['delivery_address'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="delivery-fee" class="form-label">Delivery Fee</label>
                            <input type="number" name="delivery_fee" id="delivery-fee" class="form-control" value="{{ $cartData['order_info']['delivery_fee'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="delivery-rider" class="form-label">Assign Rider</label>
                            <select class="form-select" name="delivery_rider_id" id="delivery-rider">
                                <option value="">--Select Rider--</option>
                                @foreach(getModelList('delivery-riders') as $rider)
                                <option value="{{ $rider->id }}"
                                    {{ (isset($cartData['order_info']['delivery_rider_id']) && $cartData['order_info']['delivery_rider_id'] == $rider->id) ? 'selected' : '' }}>
                                    {{ $rider->name }} ({{ $rider->phone }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="delivery-notes" class="form-label">Additional Notes</label>
                            <textarea name="delivery_notes" id="delivery-notes" class="form-control" rows="3">{{ $cartData['order_info']['delivery_notes'] ?? '' }}</textarea>
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
                                <input type="number" min="0" step="any" class="form-control" name="amount" id="amount-paid" value="{{ $cartData['order_info']['total_amount'] ?? '' }}">
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
                            <li class="list-group-item"><strong>Rider:</strong> <span id="summary-rider"></span></li>
                            <li class="list-group-item"><strong>Delivery Fee:</strong> ₦ <span id="summary-fee"></span></li>
                            <li class="list-group-item"><strong>Notes:</strong> <span id="summary-notes"></span></li>
                            <li class="list-group-item"><strong>Amount:</strong> ₦<span id="summary-amount"></span></li>
                            <li class="list-group-item"><strong>Payment Mode:</strong> <span id="summary-mode"></span></li>
                        </ul>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-secondary" type="button" id="back-to-payment">Back</button>
                            <input type="hidden" id="selected-customer-id" name="customer_id" value="{{ $cartData['order_info']['customer_id'] ?? '' }}">
                            <input type="hidden" name="delivery_area_id" id="form-delivery-area" value="{{ $cartData['order_info']['delivery_area_id'] ?? '' }}">
                            <input type="hidden" name="delivery_address" id="form-delivery-address" value="{{ $cartData['order_info']['delivery_address'] ?? '' }}">
                            <input type="hidden" name="delivery_rider_id" id="form-delivery-rider" value="{{ $cartData['order_info']['delivery_rider_id'] ?? '' }}">
                            <input type="hidden" name="delivery_fee" id="form-delivery-fee" value="{{ $cartData['order_info']['delivery_fee'] ?? '' }}">
                            <input type="hidden" name="delivery_notes" id="form-delivery-notes" value="{{ $cartData['order_info']['delivery_notes'] ?? '' }}">
                            <input type="hidden" name="order_cart_id" class="orderCartId" value="{{$orderCartId}}">
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
                        html += `<div id="customer-option" class="customer-option" data-id="${customer.id}">${customer.first_name}, ${customer.last_name} (${customer.phone})</div>`;
                    });
                } else {
                    html = '<p>No customer found.</p>';
                }
                $('#customer-results').html(html);
            });
        });

        // Select customer
        $(document).on('click', '.customer-option', async function() {
            const customerId = $(this).data('id');
            $('#selected-customer-id').val(customerId);
            //update order info
            updateOrderInformation('selected_customer_id');
            //update last address
            baseUrl = "{{url('/')}}"
            try {
                const response = await fetch(`${baseUrl}/customers/${customerId}/last-delivery`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    $('#delivery-area').val(data.delivery_area_name ?? '');
                    $('#delivery-address').val(data.delivery_address ?? '');

                    // persist to cart/order info
                    updateOrderInformation('delivery_area_name');
                    updateOrderInformation('delivery_address');
                }
            } catch (e) {
                console.error('Failed to fetch last delivery info', e);
            }

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
                console.log(data);
                $('#selected-customer-id').val(data.id);
                html = `<div id="customer-option" class="customer-option" data-id="${data.id}">${data.first_name}, (${data.phone})</div>`;
                $('#customer-results').html(html);
                $('#step-customer').addClass('d-none');
                $('#step-delivery').removeClass('d-none');

                //update order info
                updateOrderInformation('selected_customer_id');
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
            //update order info
            updateOrderInformation('selected_customer_id');

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

            $('#amount-paid').val(parseFloat($('#amount-paid').val() || 0) + parseFloat($('#delivery-fee').val() || 0));

            //update order information
            updateOrderInformation('selected_delivery_area_id');

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
            $('#summary-area').text($('#delivery-area').val());
            $('#summary-address').text($('#delivery-address').val());
            $('#summary-rider').text($('#delivery-rider option:selected').text());
            $('#summary-fee').text($('#delivery-fee').val());
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
            $('#form-delivery-rider').val($('#delivery-rider').val());
            $('#form-delivery-fee').val($('#delivery-fee').val());
            $('#form-delivery-notes').val($('#delivery-notes').val());
        });

    });

    function updateOrderInformation(fieldName) {

        if (!orderCartId) {
            console.error('Order Cart ID is missing.');
            return;
        }

        let data = {
            _token: '{{ csrf_token() }}',
            order_cart_id: orderCartId,
        };

        // Add only the changed field to the payload
        switch (fieldName) {
            case 'selected_customer_id':
                data.selected_customer_id = $('#selected-customer-id').val();
                data.customer_name = $('.customer-option[data-id="' + $('#selected-customer-id').val() + '"]').text()
                break;
            case 'selected_table_id':
                data.selected_table_id = $('#selected-table-id').val();
                break;
            case 'selected_delivery_area_id':
                data.delivery_area_id = $('#delivery-area').val();
                data.delivery_address = $('#delivery-address').val();
                data.delivery_rider_id = $('#delivery-rider').val();
                data.delivery_fee = $('#delivery-fee').val();
                data.delivery_notes = $('#delivery-notes').val();
                break;
            default:
                console.warn('Unknown field passed to updateOrderInformation');
                return;
        }

        $.ajax({
            url: '{{ route("cart.update.order-info") }}',
            method: 'POST',
            data: data,
            success: function(response) {
                console.log(response);
            },
            error: function() {
                alert('Failed to update order info.');
            }
        });
    }
</script>