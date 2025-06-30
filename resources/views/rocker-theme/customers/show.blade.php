@extends('rocker-theme.layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <img src="{{ url('assets/images/avatars/avatar-1.png') }}" alt="Admin" class="rounded-circle p-1 bg-primary" width="110">
            <div class=" ms-4">
                <h4>{{$customer->name()}}</h4>
                <p class="text-secondary mb-1">{{$customer->phone}}</p>
                <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                <a class="ms-3 btn" href="{{route('customers.edit',$customer->id)}}"><i class="fadeIn animated bx bx-edit-alt"></i></a>
                <a class="ms-3 btn delete-resource" href="javascript:void(0);" data-resource-id="{{$customer->id}}" data-resource-url="{{url('customers')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="fadeIn animated bx bx-eraser"></i></a>
            </div>
            <div class="ms-4" style="width: 40%;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <p class="mb-0">First Visit</p>
                        <span class="text-secondary">{{$metrics['first_visit']}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <p class="mb-0">Last Visit</p>
                        <span class="text-secondary">{{$metrics['last_visit']}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <p class="mb-0">Visits</p>
                        <span class="text-secondary">{{$metrics['total_visits']}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <p class="mb-0">Total Spend</p>
                        <span class="text-secondary">{{formatCurrency($metrics['total_spend'])}}</span>
                    </li>
                </ul>
            </div>
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
        <small>Click on the order reference to view order details</small>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="order-data-table" class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>Ref</th>
                        <th>Customer</th>
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
                            <a href="{{route('orders.show',$order->id)}}"><small>{{$order->reference}}</small></a>
                        </td>
                        <td>
                            @if ($order->customer)
                            <a href="{{route('customers.show',$order->customer->id)}}">{{ $order->customer->name() }}</a>
                            @else
                            Walkin Customer
                            @endif
                        </td>
                        <td>{{$order->items_string }}</td>
                        <td>@if($order->status == 'settled')
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
                                <a href="{{route('orders.show',$order->id)}}" class=""><i class="bx bxs-show"></i></a>
                                <a class="ms-3" href="{{route('printer.order',$order->id)}}" class=""><i class="bx bxs-printer"></i></a>
                                @role('Manager')
                                <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$order->id}}" data-resource-url="{{url('orders')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>
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

@include('rocker-theme.layouts.partials.delete-modal')

<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection