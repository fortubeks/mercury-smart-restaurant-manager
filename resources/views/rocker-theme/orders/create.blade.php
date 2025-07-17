@extends('rocker-theme.layouts.app')
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
<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center gap-3">
                    <div class="me-3">
                        <a href="{{route('orders.index')}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-chevron-left-circle"></i>Back to orders</a>
                    </div>
                    <div style="width: 80%;">
                        <div class="row">
                            <div class="col">
                                <input type="text" id="searchItems" class="form-control ps-5 radius-30" placeholder="Search Items">
                            </div>
                            <div class="col">
                                <select id="outlet" class="form-select" data-outlet="{{ $outletId }}">
                                    @foreach (getModelList('restaurant-outlets') as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#items" role="tab"
                                    aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">All Food</div>
                                    </div>
                                </a>
                            </li>
                            @foreach ($menuCategories as $category)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#{{ $category->id }}" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-category font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">{{ $category->name }}</div>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade active show" id="items" role="tabpanel">
                                <div class="d-flex flex-wrap gap-2 menu-items">
                                    @foreach ($menuItems as $item)
                                    <button type="button" class="btn btn-outline-primary p-2 add-to-cart d-flex flex-column align-items-center text-center"
                                        style="width: 100px; height: 120px;" data-item-id="{{ $item->id }}">

                                        @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" class="mb-1">
                                        @else
                                        <div class="mb-1" style="width: 50px; height: 50px; background-color: #e9ecef; border-radius: 6px;"></div>
                                        @endif

                                        <small class="text-wrap">{{ $item->name }} [{{ formatCurrency($item->price) }}] ({{ $item->quantity }})</small>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @foreach ($menuCategories as $category)
                            <div class="tab-pane fade" id="{{ $category->id }}" role="tabpanel">
                                <div class="d-flex flex-wrap gap-2 menu-items">
                                    @foreach ($category->menuItems as $item)
                                    <button type="button" class="btn btn-outline-primary p-2 add-to-cart d-flex flex-column align-items-center text-center"
                                        style="width: 100px; height: 120px;" data-item-id="{{ $item->id }}">

                                        @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" class="mb-1">
                                        @else
                                        <div class="mb-1" style="width: 50px; height: 50px; background-color: #e9ecef; border-radius: 6px;"></div>
                                        @endif

                                        <small class="text-wrap">{{ $item->name }}[{{ formatCurrency($item->price) }}] ({{ $item->quantity }})</small>
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        @if(isset($cartData) && count($cartData['items']) > 0)
                        <div class="cart-items">
                            @foreach ($cartData['items'] as $itemId => $item)
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
                        </div>
                        @else
                        <div class="cart-items">
                            <p>Your cart is empty.</p>
                        </div>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="selected-table-id" class="form-label">Table</label>
                                <select class="form-select selectpicker update-order-info" id="selected-table-id" data-table="">
                                    <option value="">--No Table--</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="cart-math">
                        <p class="cart-math-item">
                            <span class="cart-math-header">Total:</span>
                            <span class="g-price total">₦{{ $cartData['order_info']['total_amount'] ?? 0 }}</span>
                        </p>
                    </div>
                    @if(!$dailySalesRecord)
                    <div class="d-flex justify-content-end m-3 mb-3">
                        <!-- radio button to select dine in or take out or pick up -->
                        <div class="form-check form-check-inline">
                            <input class="form-check-input update-order-info" type="radio" name="order_type" id="dine_in" value="dine_in">
                            <label class="form-check-label" for="dine_in">Dine In</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input update-order-info" type="radio" name="order_type" id="take_away" value="take_away">
                            <label class="form-check-label" for="take_away">Take Away</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input update-order-info" type="radio" name="order_type" id="delivery" value="delivery" checked>
                            <label class="form-check-label" for="delivery">Delivery</label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end m-3">
                        <a href="{{ url('order/print-cart/'. $orderCartId ) }}" class="btn btn-outline-primary btn-sm px-2 ms-3">Print Bill</a>
                        <a href="{{ url('order/print-docket/'. $orderCartId ) }}" class="btn btn-outline-primary btn-sm px-2 ms-3">Print Docket</a>
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 ms-3 clear-cart">Clear</button>
                        <button type="button" class="btn btn-sm btn-outline-success px-2 ms-3" data-bs-toggle="modal" data-bs-target="#orderModal">Proceed </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('rocker-theme.orders.partials.order-modal')

<script>
    var orderCartId = '{{ $orderCartId }}';
</script>
<script>
    window.addEventListener('load', function() {
        $("#wrapper").addClass('toggled');
        $('.orderCartId').val(orderCartId);

        var outletId = $('#outlet').data('outlet');
        // Set the selected option based on the outlet id
        if (outletId) {
            $('#outlet').val(outletId);
        }

        const itemsDiv = $('.menu-items');
        // Delegate the click event to a parent element (e.g., itemsDiv)
        itemsDiv.on('click', '.add-to-cart', function() {

            var itemId = $(this).data('item-id');
            $.ajax({
                url: "{{ url('cart/add') }}",
                method: 'POST',
                data: {
                    item_id: itemId,
                    order_cart_id: orderCartId,
                    _token: '{{ csrf_token() }}'
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

        $('.clear-cart').click(function() {
            $.ajax({
                url: "{{ url('cart/clear') }}",
                method: 'post',
                success: function(response) {
                    updateCartItems(response);
                    // Optionally, update the cart count or display a success message
                },
                data: {
                    order_cart_id: orderCartId,
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
                url: "{{ url('cart/update') }}",
                method: 'POST',
                data: {
                    item_id: itemId,
                    quantity: quantity,
                    price: price,
                    order_cart_id: orderCartId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
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
                url: "{{ url('cart/remove') }}",
                method: 'POST',
                data: {
                    item_id: itemId,
                    order_cart_id: orderCartId,
                    _token: '{{ csrf_token() }}'
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
                    outletId: selectedOutletId,
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

        // Cancel order
        $('.cancel-order').click(function() {
            // Get the CSRF token from the meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send the POST request with the CSRF token included
            $.post({
                url: "{{ url('restaurant-cart/clear') }}",
                data: {
                    _token: csrfToken,
                    order_cart_id: orderCartId
                },
                success: function(data) {
                    // Handle response data
                    //console.log(data);
                    updateCartItems();
                }
            });
        });

        // Set the placeholder option as selected
        $('#outlet option[value=""]').prop('selected', true);

        // Select the search input field
        const searchInput = $('#searchItems');

        // Add keyup event listener to the search input field
        searchInput.on('keyup', function() {
            // Get the search query
            const query = searchInput.val().trim();

            // Send an AJAX request to the server
            $.ajax({
                url: "{{ url('search/menu-items') }}",
                method: 'GET',
                data: {
                    query: query
                },
                success: function(data) {
                    //console.log(data);
                    // Update the items div with the filtered items
                    const itemsDiv = $('.menu-items');
                    itemsDiv.empty(); // Clear previous items

                    $.each(data, function(index, item) {
                        const button = $('<button>', {
                            type: 'button',
                            class: 'btn btn-outline-primary p-2 add-to-cart d-flex flex-column align-items-center text-center',
                            style: 'width: 100px; height: 120px;',
                            'data-item-id': item.id
                        });

                        // Image or placeholder
                        if (item.image) {
                            const img = $('<img>', {
                                src: '/storage/' + item.image,
                                alt: item.name,
                                style: 'width: 50px; height: 50px; object-fit: cover; border-radius: 6px;',
                                class: 'mb-1'
                            });
                            button.append(img);
                        } else {
                            const placeholder = $('<div>', {
                                class: 'mb-1',
                                style: 'width: 50px; height: 50px; background-color: #e9ecef; border-radius: 6px;'
                            });
                            button.append(placeholder);
                        }

                        // Name and quantity
                        const label = $('<small>', {
                            class: 'text-wrap',
                            text: item.name + ' (' + item.quantity + ')'
                        });

                        button.append(label);
                        itemsDiv.append(button);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
        //end
    });

    function updateCartItems(response) {
        // Parse JSON response
        var cartData = JSON.parse(response.cart);
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
                            <p style="text-align: right;">${item.price} x <span class="quantity">${item.quantity}</span></p>
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