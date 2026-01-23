@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center gap-3">
                    <!-- <div class="position-relative">
                        <input type="text" class="form-control ps-5 radius-30" placeholder="Search by Guest or Room No"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                    </div> -->
                    <div class="position-relative">
                        <h5>{{$order->reference}}</h5>
                    </div>
                    <div class="ms-auto">
                        <a href="{{url('orders')}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="lni lni-chevron-left-circle"></i>Back to Orders</a>
                        @if(!$order->hasBeenPaid())
                        <a href="#" class="btn btn-primary radius-30 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bxs-credit-card"></i>Add Payment</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center gap-3">
                    <div class="position-relative">
                        <h5>Order details</h5>
                        <h6>Customer: @if ($order->customer)
                            <a href="{{ route('customers.show', $order->customer_id) }}">{{ $order->customer->name() }}</a>
                            @else
                            Walkin Customer
                            @endif
                        </h6>
                        <h6>Date: {{formatDate($order->order_date)}}</h6>
                        <h6>Created By: {{$order->createdBy->name}}</h6>
                    </div>
                    <div class="position-relative">
                        <h5>Delivery Details</h5>
                        <div id="rider-section-{{ $order->id }}">
                            <h6>
                                @if ($order->deliveryRider)
                                <a href="{{ route('delivery-riders.show', $order->delivery_rider_id) }}">
                                    Delivery Rider: {{ $order->deliveryRider->name }}
                                </a>
                                @else
                                <div class="d-flex align-items-center gap-2">
                                    <select id="rider_select_{{ $order->id }}" class="rider-select form-select" data-order-id="{{ $order->id }}">
                                        <option value="">-- Select Rider --</option>
                                        @foreach($availableRiders as $rider)
                                        <option value="{{ $rider->id }}">{{ $rider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </h6>
                        </div>

                        <h6>@if ($order->deliveryArea)
                            Delivery Area: {{ $order->deliveryArea->name }}</a>
                            @else
                            N/A
                            @endif</h6>
                        <h6>Delivery Address: {{$order->delivery_address ?? 'N/A'}}</h6>
                    </div>
                    <div class="ms-auto">
                        <a href="{{route('printer.order',$order->id)}}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="lni lni-printer"></i>Print Order</a>
                        @role('Manager')
                        <a href="javascript:void(0);" data-resource-id="{{$order->id}}" data-resource-url="{{url('orders')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal" class="btn btn-danger radius-30 mt-2 mt-lg-0 delete-resource"><i class="lni lni-trash"></i>Delete Order</a>
                        @endrole
                        @if(!$order->status == 'settled')
                        <a href="{{url('order/print/'.$order->id)}}" class="btn btn-primary radius-30 mt-2 mt-lg-0" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bxs-credit-card"></i>Add Payment</a>
                        @endif
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
                            @foreach($order->menuItems as $key => $item)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$item->name ?? ''}}</td>
                                <td>{{$item->pivot->qty ?? ''}}</td>
                                <td>{{formatCurrency($item->pivot->total_amount)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"></td>
                            </tr>
                            <tr>
                                <td colspan="3">Delivery fee</td>
                                <td>{{formatCurrency($order->delivery_fee)}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>{{formatCurrency($order->total_amount)}}</th>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Payments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->payments as $key => $payment)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $order->order_date)->format('d-m-y') }}</td>
                                    <td class="text-left">{{ $payment->payment_method }}</td>
                                    <td class="total">{{ formatCurrency($payment->amount) }}</td>
                                    <td><a title="Delete" href="javascript:void(0);" class="ms-3 delete-resource" data-resource-id="{{$payment->id}}" data-resource-url="{{url('incoming-payments')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a></td>
                                </tr>
                                @empty
                                No payment yet
                                @endforelse

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan=""></td>
                                    <td colspan="">TOTAL PAYMENTS</td>
                                    <td colspan="">{{ formatCurrency($order->totalPayments) }}</td>
                                </tr>
                                <tr>
                                    <td colspan=""></td>
                                    <td colspan="">AMOUNT DUE:</td>
                                    <td colspan="">{{ formatCurrency($order->amountDue) }} </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('rocker-theme.layouts.partials.delete-modal')
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
                            <form method="post" action="{{ route('order.add-payment') }}">
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
                                        <label for="phone">Payment Receipt Reference <small class="text-danger">(Required only if POS/Transfer method is selected)</small></label>
                                        <input type="text" disabled id="reference_number" class="form-control" name="reference_number" placeholder="Reference Number">
                                    </div>
                                    <div class="col-12">
                                        <label for="inputCompareatprice" class="form-label">Date of Payment</label>
                                        <input type="date" required class="form-control datepicker flatpickr-input active"
                                            name="date_of_payment" data-toggle="flatpickr">
                                    </div>
                                    <div class="col-12">
                                        <label for="inputCompareatprice" class="form-label">Amount Paid</label>
                                        <input type="number" class="form-control" name="amount" id="amount-paid"
                                            placeholder="00.00" max="{{$amount_due}}">
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check form-check-primary">
                                            <input class="form-check-input" type="checkbox" value="yes" name="settlement" id="flexCheckSuccess">
                                            <label class="form-check-label" for="flexCheckSuccess">
                                                Settlement for a different shift?
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-grid">
                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
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

    <script>
        window.addEventListener('load', function() {

            $(document).on('change', '.rider-select', function() {
                let riderId = $(this).val();
                let orderId = $(this).data('order-id');

                if (riderId) {
                    $.ajax({
                        url: '{{ route("orders.assignRider") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order_id: orderId,
                            rider_id: riderId
                        },
                        success: function(response) {
                            $('#rider-section-' + orderId).html(response.html);
                        },
                        error: function(xhr) {
                            alert('Failed to assign rider.');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>