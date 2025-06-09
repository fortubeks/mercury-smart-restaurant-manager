window.addEventListener('load', function () {
    $("#wrapper").addClass('toggled');
    $('.orderCartId').val(orderCartId);

    var outletId = $('#outlet').data('outlet');
    // Set the selected option based on the outlet id
    if (outletId) {
        $('#outlet').val(outletId);
    }

    const itemsDiv = $('.menu-items');
    // Delegate the click event to a parent element (e.g., itemsDiv)
    itemsDiv.on('click', '.add-to-cart', function () {

        var itemId = $(this).data('item-id');
        $.ajax({
            url: "{{ url('cart/add') }}",
            method: 'POST',
            data: {
                item_id: itemId,
                order_cart_id: orderCartId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                updateCartItems(response);
                // Optionally, update the cart count or display a success message
            },
            error: function (xhr, status, error) {
                console.log('Error: ' + xhr.responseText);
            }
        });
    });
    $('.update-order-info').change(function () {
        var selectedGuestId = $('.guest_id').val();
        var selectedRoomId = $('#room_id').val();
        // Send an AJAX post request to update session value
        $.ajax({
            url: "{{ url('update-order-info') }}",
            method: 'post',
            data: {
                selected_guest_id: selectedGuestId,
                selected_room_id: selectedRoomId,
                order_cart_id: orderCartId,
                _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
            },
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.error(error);
                // Handle errors if needed
            }
        });
    });
    $('.clear-cart').click(function () {
        $.ajax({
            url: "{{ url('cart/clear') }}",
            method: 'post',
            success: function (response) {
                updateCartItems(response);
                // Optionally, update the cart count or display a success message
            },
            data: {
                order_cart_id: orderCartId,
                _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
            },
            error: function (xhr, status, error) {
                console.log('Error: ' + xhr.responseText);
            }
        });
    });
    $('.cart-items').on('click', '.update-cart', function () {
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
            success: function (response) {
                console.log(response);
                // Update the quantity in the view
                $item.find('.quantity').text(quantity);
                $item.find('.price').text(response.item_price);
                updateCartItems(response)
            },
            error: function (xhr, status, error) {
                console.log('Error: ' + xhr.responseText);
            }
        });
    });

    $('.cart-items').on('click', '.remove-from-cart', function () {
        var itemId = $(this).closest('.cart-item').data('item-id');

        $.ajax({
            url: "{{ url('cart/remove') }}",
            method: 'POST',
            data: {
                item_id: itemId,
                order_cart_id: orderCartId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                // Remove the item from the view
                //$('[data-item-id="' + itemId + '"]').remove();
                updateCartItems(response)
            },
            error: function (xhr, status, error) {
                console.log('Error: ' + xhr.responseText);
            }
        });
    });

    $('#outlet').change(function () {
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
            success: function (response) {
                window.location.reload(); // Reload the page after successful update
            },
            error: function (xhr, status, error) {
                console.error(error);
                // Handle errors if needed
            }
        });
    });

    // Cancel order
    $('.cancel-order').click(function () {
        // Get the CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Send the POST request with the CSRF token included
        $.post({
            url: "{{ url('restaurant-cart/clear') }}",
            data: {
                _token: csrfToken,
                order_cart_id: orderCartId
            },
            success: function (data) {
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
    searchInput.on('keyup', function () {
        // Get the search query
        const query = searchInput.val().trim();

        // Send an AJAX request to the server
        $.ajax({
            url: "{{ url('search/menu-items') }}",
            method: 'GET',
            data: {
                query: query
            },
            success: function (data) {
                //console.log(data);
                // Update the items div with the filtered items
                const itemsDiv = $('.menu-items');
                itemsDiv.empty(); // Clear previous items

                $.each(data, function (index, item) {
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
            error: function (xhr, status, error) {
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
    jQuery.each(cartData.items, function (index, item) {
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