@extends('dashboard.layouts.app')
<style>
    .cart-item {
        display: flex;
        align-items: flex-start;
        padding-bottom: 25px;
        margin-bottom: 25px;
        border-bottom: 1px solid #D7D7F9;
        animation: fadeIn 0.3s;
    }

    .cart-item:last-child {
        border-bottom: 5px solid #D7D7F9;
    }

    .cart-item img {
        width: 65px;
    }

    .cart-item .g-price {
        font-size: 14px;
    }

    .cart-item-dets {
        margin-left: 15px;
        width: 100%;
    }

    .cart-item-heading {
        margin: 10px 0;
    }

    .cart-math-item {
        margin: 5px 0;
        font-weight: 700;
    }

    .cart-math-item span {
        display: inline-block;
        text-align: right;
    }

    .cart-math-item .cart-math-header {
        width: 50%;
    }

    .cart-math-item .g-price {
        width: 40%;
    }
</style>
@section('contents')
<div class="page-content">

    <div class="row">
        <div class="col-md-12 mb-4">
            <a href="{{url('restaurant-orders')}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-chevron-left-circle"></i>Back to orders</a>
        </div>
        <div class="col-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col">
                            <input type="text" id="searchItems" class="form-control ps-5 radius-30" placeholder="Search Items">
                        </div>
                        <div class="col">
                            <select id="outlet" class="form-select" data-outlet="{{ $outlet_id }}">
                                <option>--Select Restaurant--</option>
                                @foreach(getModelList('restaurant-outlets') as $restaurant)
                                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">All Items</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item d-none" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false" tabindex="-1">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Beer</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item d-none" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#primarycontact" role="tab" aria-selected="false" tabindex="-1">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-microphone font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Others</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane fade active show" id="items" role="tabpanel">
                            @foreach($items as $item)
                            <button type="button" class="btn btn-outline-primary btn-lg add-to-cart mx-2 mt-3"
                                data-item-id="{{ $item->id }}">{{ $item->name }}({{ $item->quantity }})</button>
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                        </div>
                        <div class="tab-pane fade" id="primarycontact" role="tabpanel">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mx-auto">
            <div class="card">

                <div class="card-body p-4">
                    @php $cart = $restaurantCartData;
                    //dd($restaurantCartData);
                    @endphp
                    <div class="cart-items">
                        @if(count($cart ?? []) > 0)

                        @foreach ($cart['items'] as $itemId => $item)
                        <div class="cart-item" data-item-id="{{ $itemId }}">

                            <div class="cart-item-dets">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="">{{ $item['name'] }} </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="text-align: right;">₦{{ number_format($item['price'], 2) }} x <span class="quantity">{{ $item['quantity'] }}</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="number" class="quantity-input form-control" value="{{ $item['quantity'] }}" min="1">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="price-input form-control" value="{{ $item['price'] }}">
                                    </div>
                                    <div class="col-md-4 d-flex order-actions">
                                        <a href="javascript:;" class="ms-3 update-cart"><i class="bx bxs-edit"></i></a>
                                        <a href="javascript:;" class="ms-3 remove-from-cart"><i class="bx bxs-trash"></i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                        @else
                        <p>Your cart is empty.</p>
                        @endif
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col">
                            <label for="single-select-field" class="form-label">Room</label>
                            <select class="form-select selectpicker update-order-info" id="room_id">
                                <option value="">--No Room--</option>
                                @foreach (getAllCheckedInRoomReservations() as $reservation)
                                <option value="{{ $reservation->room_id }}">{{ $reservation->room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="single-select-field" class="form-label">Guest</label>
                            <select class="form-select selectpicker update-order-info guest_id" id="single-select-field" data-guest="">
                                <option value="">--No Guest--</option>
                                @foreach (getModelList('guests') as $guest)
                                <option value="{{ $guest->id }}">{{ $guest->name() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input type="checkbox" id="charge-company" name="charge_company" class="form-check-input update-order-info">
                            <label for="charge-company" class="form-check-label">Charge to company</label>
                        </div>
                    </div>
                </div>

                <div class="cart-math">
                    <p class="cart-math-item">
                        <span class="cart-math-header">Total:</span>
                        <span class="g-price total">₦{{ number_format( $cart['order_info']['total_amount'], 2) }}</span>
                    </p>
                </div>

                @if(!$daily_sales_record)
                <div class="d-flex justify-content-end m-3">
                    <a href="{{ url('restaurant-order/print-cart/'. $restaurantCartOrderId ) }}" class="btn btn-outline-primary btn-sm px-2 ms-3">Print Bill</a>
                    <a href="{{ url('restaurant-order/print-docket/'. $restaurantCartOrderId ) }}" class="btn btn-outline-primary btn-sm px-2 ms-3">Print Docket</a>
                    <button type="button" class="btn btn-sm btn-outline-danger px-2 ms-3 clear-cart"></i>Clear</button>
                    <button type="button" class="btn btn-sm btn-outline-success px-2 ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>Proceed </button>
                </div>
                @endif


            </div>
        </div>
    </div>
</div>
<!--end row-->
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Close out order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs nav-primary" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#add-payment" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                                </div>
                                <div class="tab-title">Add Payment</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#continue-without-payment" role="tab" aria-selected="false" tabindex="-1">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                                </div>
                                <div class="tab-title">Continue Without Payment</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content py-3">
                    <div class="tab-pane fade active show" id="add-payment" role="tabpanel">
                        <form method="post" action="{{url('restaurant-orders')}}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="inputPrice" class="form-label">Mode of Payment</label>
                                    <select class="form-select" name="mode_of_payment">
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
                                            @foreach (getModelList('bank-accounts') as $bankAccount)
                                            <option value="{{$bankAccount->id}}">{{$bankAccount->account_name}} -
                                                {{$bankAccount->account_number}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="inputCompareatprice" class="form-label">Amount Paid</label>
                                    <input type="number" min="0" step="any" class="form-control" name="amount" id="amount-paid" value="{{ $cart['order_info']['total_amount'] }}">
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <input type="hidden" name="restaurant_cart_order_id" class="restaurantOrderCartId">
                                        <input type="hidden" name="outlet_id" id="outlet_id" value="{{$outlet_id}}">
                                        <button type="submit" class="btn btn-primary">Save & close order</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="continue-without-payment" role="tabpanel">
                        <form method="post" action="{{url('restaurant-orders')}}">
                            @csrf
                            <input type="hidden" name="restaurant_cart_order_id" class="restaurantOrderCartId">
                            <input type="hidden" name="outlet_id" id="outlet_id" value="{{$outlet_id}}">
                            <input type="hidden" name="cwp" value="yes">
                            <input type="hidden" name="mode_of_payment" value="credit">
                            <input type="hidden" name="amount" id="credit-amount-paid" value="{{ $cart['order_info']['total_amount'] }}">
                            <button type="submit" class="btn btn-primary">Save as credit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Go back</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    var restaurantCartOrderId = '{{$restaurantCartOrderId}}';

    window.addEventListener('load', function() {
        $("#wrapper").addClass('toggled');
        $('.restaurantOrderCartId').val(restaurantCartOrderId);

        var outletId = $('#outlet').data('outlet');
        // Set the selected option based on the outlet id
        if (outletId) {
            $('#outlet').val(outletId);
        }

        var roomId = $('#room_id').data('room');
        // Set the selected option based on the outlet id
        if (roomId) {
            $('#room_id').val(roomId);
        }

        var guestId = $('#guest_id').data('guest');
        // Set the selected option based on the outlet id
        if (roomId) {
            $('#guest_id').val(guestId);
        }

        const itemsDiv = $('#items');
        // Delegate the click event to a parent element (e.g., itemsDiv)
        itemsDiv.on('click', '.add-to-cart', function() {
            var itemId = $(this).data('item-id');
            $.ajax({
                url: "{{ url('restaurant-cart/add') }}",
                method: 'GET',
                data: {
                    item_id: itemId,
                    restaurant_cart_order_id: restaurantCartOrderId,
                },
                success: function(response) {
                    updateCartItems(response);
                    // Optionally, update the cart count or display a success message
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + xhr.responseText);
                }
            });
        });
        $('.update-order-info').change(function() {
            var selectedGuestId = $('.guest_id').val();
            var selectedRoomId = $('#room_id').val();
            var chargeCompany = $('#charge-company').prop('checked') ? 1 : 0;
            // Send an AJAX post request to update session value
            $.ajax({
                url: "{{url('update-restaurant-order-info')}}",
                method: 'post',
                data: {
                    selected_guest_id: selectedGuestId,
                    selected_room_id: selectedRoomId,
                    charge_company: chargeCompany,
                    restaurant_cart_order_id: restaurantCartOrderId,
                    _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle errors if needed
                }
            });
        });
        $('.clear-cart').click(function() {
            $.ajax({
                url: "{{ url('restaurant-cart/clear') }}",
                method: 'post',
                success: function(response) {
                    updateCartItems(response);
                },
                data: {
                    restaurant_cart_order_id: restaurantCartOrderId,
                    _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + xhr.responseText);
                }
            });
        });
        $('.cart-items').on('click', '.update-cart', function() {
            var $item = $(this).closest('.cart-item');
            var itemId = $item.data('item-id');
            var quantity = $item.find('.quantity-input').val();
            var price = $item.find('.price-input').val();

            $.ajax({
                url: "{{ url('restaurant-cart/update') }}",
                method: 'GET',
                data: {
                    item_id: itemId,
                    quantity: quantity,
                    price: price,
                    restaurant_cart_order_id: restaurantCartOrderId
                },
                success: function(response) {
                    // Update the quantity in the view
                    $item.find('.quantity').text(quantity);
                    $item.find('.price').text(response.item_price);
                    updateCartItems(response)
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + xhr.responseText);
                }
            });
        });

        $('.cart-items').on('click', '.remove-from-cart', function() {
            var itemId = $(this).closest('.cart-item').data('item-id');

            $.ajax({
                url: "{{ url('restaurant-cart/remove') }}",
                method: 'GET',
                data: {
                    item_id: itemId,
                    restaurant_cart_order_id: restaurantCartOrderId
                },
                success: function(response) {
                    // Remove the item from the view
                    //$('[data-item-id="' + itemId + '"]').remove();
                    updateCartItems(response)
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + xhr.responseText);
                }
            });
        });

        $('#outlet').change(function() {
            // Get the selected value
            var selectedOutletId = $(this).val();

            // Send an AJAX GET request to update session value
            $.ajax({
                url: "{{ url('set-outlet') }}",
                method: 'post',
                data: {
                    outlet_id: selectedOutletId,
                    _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                },
                success: function(response) {
                    window.location.reload(); // Reload the page after successful update
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle errors if needed
                }
            });
        });

        // Select the search input field
        const searchInput = $('#searchItems');

        // Add keyup event listener to the search input field
        searchInput.on('keyup', function() {
            // Get the search query
            const query = searchInput.val().trim();

            // Send an AJAX request to the server
            $.ajax({
                url: "{{ url('restaurant-cart/search-items') }}",
                method: 'GET',
                data: {
                    query: query
                },
                success: function(data) {
                    //console.log(data);
                    // Update the items div with the filtered items
                    const itemsDiv = $('#items');
                    itemsDiv.empty(); // Clear previous items

                    $.each(data, function(index, item) {
                        const button = $('<button>').attr({
                            type: 'button',
                            class: 'btn btn-outline-primary btn-lg add-to-cart mx-2 mt-3',
                            'data-item-id': item.id
                        }).text(item.store_item.name + ' (' + item.qty + ')');

                        itemsDiv.append(button);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

    });

    function updateCartItems(response) {
        // Parse JSON response
        var cartData = JSON.parse(response.cart);
        //console.log(cartData);
        // Clear existing cart items
        $('.cart-items').empty();

        let total = 0;
        // Rebuild cart items
        jQuery.each(cartData.items, function(index, item) {
            var html = `
                <div class="cart-item" data-item-id="${index}" data-price="${item.price}">
                    <div class="cart-item-dets">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="">${item.name} </p>
                        </div>
                        <div class="col-md-6">
                            <p style="text-align: right;">${formatCurrency(item.price)} x <span class="quantity">${item.quantity}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input type="number" class="quantity-input form-control" value="${item.quantity}" min="1">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="price-input form-control" value="${item.price}">
                        </div>
                        <div class="col-md-4 d-flex order-actions">
                            <a href="javascript:;" class="ms-3 update-cart"><i class="bx bxs-edit"></i></a>
                            <a href="javascript:;" class="ms-3 remove-from-cart"><i class="bx bxs-trash"></i></a>
                        </div>
                    </div>
                </div>
            `;
            $(".cart-items").append(html);
            total += item.total;
            //$('.cart-total h2').text('Total: $' + total);
        });
        $('.cart-math').html(`
    <p class="cart-math-item">
      <span class="cart-math-header">Total:</span>
      <span class="g-price total">${formatCurrency(total)}</span>
    </p>
  `);
        $('#amount-paid').val(total);
        $('#credit-amount-paid').val(total);

    }
</script>