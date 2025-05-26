@extends('dashboard.layouts.app')
@section('contents')
<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Restaurant Order </li>
                </ol>
            </nav>
        </div>

    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5>Order details</h5>
                    <h6>Guest: @if ($restaurant_order->guest)
                        <a href="{{ url('guests/' . $restaurant_order->guest->id) }}">{{ $restaurant_order->guest->name() }}</a>
                        @else
                        Walkin Guest
                        @endif
                    </h6>
                    <h6>Date: {{formatDate($restaurant_order->order_date)}}</h6>
                    <h6>Waiter: {{$restaurant_order->user ? $restaurant_order->user->name : 'Deleted Staff'}}</h6>
                </div>
                <div class="col-md-6">
                    <div class="d-lg-flex align-items-center mb-4 gap-3">
                        <div class="position-relative">
                            <a href="{{url('restaurant-order/print/'.$restaurant_order->id)}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="lni lni-printer"></i>Print Order</a>
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteOrderModal" class="btn btn-danger radius-30 mt-2 mt-lg-0"><i class="lni lni-trash"></i>Delete Order</a>
                            @if(!$restaurant_order->hasBeenPaid())
                            <a href="{{url('restaurant-order/print/'.$restaurant_order->id)}}" class="btn btn-primary radius-30 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bxs-credit-card"></i>Add Payment</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>S/n</th>
                            <th> Name</th>
                            <th>Qty</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restaurant_order->items as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->restaurantItem->name ?? ''}}</td>
                            <td>{{$item->qty ?? ''}}
            </div>
            </td>
            <td>{{formatCurrency($item->amount)}}</td>
            </tr>
            @endforeach
            </tbody>
            <tfoot>
                <th></th>
                <th></th>
                <th></th>
                <th>{{formatCurrency($restaurant_order->total_amount)}}</th>
            </tfoot>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h6>Payments</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th> Method</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restaurant_order->payments as $key => $payment)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $restaurant_order->order_date)->format('d-m-y') }}</td>
                            <td class="text-left">{{ $payment->payment_method }}</td>
                            <td class="total">{{ formatCurrency($payment->amount) }}</td>
                            <td><a href="javascript:void(0);" class="ms-3 delete-payment" data-id="{{ $payment->id }}"
                                    title="Delete payment" data-bs-toggle="modal" data-bs-target="#deletePaymentModal"><i class="bx bxs-trash"></i></a></td>
                        </tr>
                        @empty
                        No payment yet
                        @endforelse
                        <th colspan="3">Settlements</th>
                        @forelse($restaurant_order->settlements as $key => $settlement)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $settlement->payable->shift_date)->format('d-m-y') }}</td>
                            <td class="text-left">{{ $settlement->payment_method }}</td>
                            <td class="total">{{ formatCurrency($settlement->amount) }}</td>
                            <td><a href="javascript:void(0);" class="ms-3 delete-settlement" data-id="{{ $settlement->id }}"
                                    title="Delete settlement" data-bs-toggle="modal" data-bs-target="#deleteSettlementModal"><i class="bx bxs-trash"></i></a></td>
                        </tr>
                        @empty
                        No settlements
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan=""></td>
                            <td colspan="">TOTAL PAYMENTS</td>
                            <td colspan="">{{ formatCurrency($total_payments) }}</td>
                        </tr>
                        <tr>
                            <td colspan=""></td>
                            <td colspan="">AMOUNT DUE:</td>
                            <td colspan="">{{ formatCurrency($amount_due) }} </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this payment?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" id="delete-payment-form" action="{{ url('payments/') }}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel" aria-hidden="true">
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
                <form method="POST" id="order-form" action="{{url('restaurant-orders/'.$restaurant_order->id)}}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSettlementModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this settlement?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" id="delete-settlement-form" action="{{ url('delete-settlement/') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
                        <a class="nav-link active" data-bs-toggle="tab" href="#add-payment" role="tab"
                            aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                                </div>
                                <div class="tab-title">Add Payment</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content py-3">
                    <div class="tab-pane fade active show" id="add-payment" role="tabpanel">
                        <form method="post" action="{{ url('add-restaurant-order-payment') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="inputPrice" class="form-label">Mode of Payment</label>
                                    <select class="form-select" name="mode_of_payment" required>
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
                                    <label for="inputCompareatprice" class="form-label">Date of Payment</label>
                                    <input type="date" required class="form-control datepicker flatpickr-input active"
                                        name="date_of_payment" data-toggle="flatpickr">
                                </div>
                                <div class="col-12">
                                    <label for="inputCompareatprice" class="form-label">Amount Paid</label>
                                    <input type="number" class="form-control" name="amount" id="amount-paid"
                                        value="{{$amount_due}}" max="{{$amount_due}}">
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-check-primary">
                                        <input class="form-check-input" type="checkbox" value="yes" name="settlement" id="flexCheckSuccess">
                                        <label class="form-check-label" for="flexCheckSuccess">
                                            Select if this payment was made on a different shift from invoice date
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <input type="hidden" name="restaurant_order_id" value="{{ $restaurant_order->id }}">
                                        <button type="submit" class="btn btn-primary">Save & close order</button>
                                    </div>
                                </div>
                            </div>
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
    window.addEventListener('load', function() {

        $(".delete-order").click(function(event) {
            var orderId = $(this).data('orderId');
            var currentUrl = "{{ url('restaurant-orders') }}"

            // Construct the new URL with appended bar order ID
            var newUrl = currentUrl + "/" + orderId;

            // Update the form action attribute with the new URL
            $("#order-form").attr("action", newUrl);

            // Submit the form with the updated action
            //$("#bar-form").submit();
        });

        $(".delete-payment").click(function(event) {
            var id = $(this).data('id');
            var currentUrl = "{{ url('payments') }}";

            // Construct the new URL with appended bar order ID
            var newUrl = currentUrl + "/" + id;

            // Update the form action attribute with the new URL
            $("#delete-payment-form").attr("action", newUrl);
        });

        $(".delete-settlement").click(function(event) {
            var id = $(this).data('id');
            var currentUrl = "{{ url('delete-settlement') }}";

            // Construct the new URL with appended bar order ID
            var newUrl = currentUrl + "/" + id;

            // Update the form action attribute with the new URL
            $("#delete-settlement-form").attr("action", newUrl);
        });

    });
</script>