@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <!-- <div class="position-relative">
                        <input type="text" class="form-control ps-5 radius-30" placeholder="Search by Guest or Room No"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                    </div> -->
                    <div class="position-relative">

                    </div>
                    <div class="position-relative">
                        <select id="outlet" class="form-select" data-outlet="{{ $outletId }}">
                            @foreach (getModelList('restaurant-outlets') as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ms-auto"><a href="{{url('restaurant-orders/create')}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Create New Order</a></div>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order Time</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($restaurantCartOrders as $key => $order)
                            <tr>
                                <td>{{ Carbon\Carbon::createFromTimestamp($order['restaurantCartOrderId'])->format('H:i:s') }}</td>
                                <td>
                                    <div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3"><i class="bx bxs-circle me-1"></i>Open</div>
                                </td>
                                <td>{{formatCurrency($order['totalAmount'])}}</td>
                                <td>{{Carbon\Carbon::createFromTimestamp($order['restaurantCartOrderId'])->format('g:iA') }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a title="Edit" href="{{url('restaurant-cart/edit?id='.$order['restaurantCartOrderId'])}}"><i class="bx bxs-edit"></i></a>
                                        <a class="ms-3" title="Print Bill" href="{{ url('restaurant-order/print-cart/'. $order['restaurantCartOrderId'] ) }}"><i class="bx bxs-printer"></i> </a>
                                        <a class="ms-3" title="Print Docket" href="{{ url('restaurant-order/print-docket/'. $order['restaurantCartOrderId'] ) }}"><i class="bx bxs-copy-alt"></i> </a>
                                        <a href="javascript:void(0);" class="ms-3 delete-cart" data-order-id="{{ $order['restaurantCartOrderId'] }}" data-bs-toggle="modal" data-bs-target="#deleteCartModal"><i class="bx bxs-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show">
                                <div>No Orders in cart</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="chip">Cash: {{formatCurrency($sales['cash'])}}</div>
                    <div class="chip">POS:{{formatCurrency($sales['pos'])}}</div>
                    <div class="chip">Transfer: {{formatCurrency($sales['transfer'])}}</div>
                    <div class="chip">Credit: {{formatCurrency($sales['credit'])}}</div>
                    <div class="chip">Wallet: {{formatCurrency($sales['wallet'])}}</div>
                    <div class="chip">Total: {{formatCurrency($sales['total'])}}</div>
                </div>
                <small>Click on the order number to view order details</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="restaurant-order-data-table" class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Time</th>
                                <th>Order No</th>
                                <th>Guest</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $key => $order)
                            <tr>
                                <td>{{$order->created_at->format('g:iA')}}</td>
                                <td>
                                    <a href="{{url('restaurant-orders/'.$order->id)}}">{{$order->id}}</a>
                                </td>
                                <td>
                                    @if ($order->guest)
                                    <a href="{{ url('guests/' . $order->guest->id) }}">{{ $order->guest->name() }}</a>
                                    @else
                                    Walkin Guest
                                    @endif
                                </td>
                                <td>{{$order->items_string }}</td>

                                <td>@if($order->hasBeenPaid())
                                    <div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">
                                        <i class="bx bxs-circle me-1"></i>Settled
                                    </div>
                                    @else
                                    <div class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3">
                                        <i class="bx bxs-circle me-1"></i>Unsettled
                                    </div>
                                    @endif
                                </td>
                                <td>{{formatCurrency($order->total_amount)}}</td>
                                <td>{{$order->payment_details}}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{url('restaurant-orders/'.$order->id)}}" class=""><i class="bx bxs-show"></i></a>
                                        <a class="ms-3" href="{{url('restaurant-order/print/'.$order->id)}}" class=""><i class="bx bxs-printer"></i></a>
                                        @role('Manager')
                                        <a class="ms-3 delete-order" href="javascript:void(0);" data-order-id="{{$order->id}}" data-bs-toggle="modal" data-bs-target="#deleteOrderModal"><i class="bx bxs-trash"></i></a>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show">
                                <div>No Orders yet</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="deleteCartModal" tabindex="-1" aria-labelledby="deleteCartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this restaurant cart order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" action="{{url('restaurant-cart/delete')}}">
                        @csrf
                        <input type="hidden" name="restaurant_cart_order_id" id="deleteCartIdInput" value="">
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteRestaurantOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this restaurant order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form method="POST" id="order-form" action="{{url('restaurant-orders/')}}">
                        @csrf @method('delete')
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        var outletId = $('#outlet').data('outlet');
        // Set the selected option based on the outlet id
        if (outletId) {
            $('#outlet').val(outletId);
        }

        var restaurant_order_table = $('#restaurant-order-data-table').DataTable({
            lengthChange: true,
            buttons: [{
                    extend: 'excel',
                    title: 'Restaurant Sales for {{$currentShift}}'
                },
                {
                    extend: 'pdf',
                    title: 'Restaurant Sales for {{$currentShift}}'
                },
                {
                    extend: 'print',
                    title: 'Sales Report',
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend('<h6 style="text-align: center;">Restaurant Sales for {{$currentShift}}</h6>'); // Add title to the print view
                    }
                }
            ],
            "order": [
                [1, "asc"]
            ],
        });

        restaurant_order_table.buttons().container().appendTo('#restaurant-order-data-table_wrapper .col-md-6:eq(0)');

        $('.delete-cart').click(function() {
            var orderId = $(this).data('orderId');
            $('#deleteCartIdInput').val(orderId);
        });
        $(".delete-order").click(function(event) {
            var orderId = $(this).data('order-id');
            var baseUrl = "{{ url('restaurant-orders') }}";

            // Construct the new URL with appended restaurant order ID
            var newUrl = baseUrl + "/" + orderId;

            // Update the form action attribute with the new URL
            $("#order-form").attr("action", newUrl);
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
    });
</script>