@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0">Store & Sales Activities</h6>
                        </div>
                        <div class="ms-auto">
                            <form action="{{route('store-items.show', $store_item->id)}}" method="GET">
                                <div class="row">
                                    <div class="col-md-4 px-4">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input class="form-control date-format" data-toggle="flatpickr" type="date"
                                                id="search_start" name="start_date" value="{{ request('start_date') ? request('start_date') : $startDate }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 px-4">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input class="form-control date-format" data-toggle="flatpickr" type="date"
                                                id="search_end" name="end_date" value="{{ request('end_date') ? request('end_date') : $endDate }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 px-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary mt-3 w-100">
                                                <i class="bx bx-search-alt me-0"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="chip">Category: {{$store_item->category->name}}</div>
                            <div class="chip">Stock Balance: {{$stockBalance}}</div>
                            <div class="chip">Units Sold: {{$store_item->quantity_sold}}</div>
                            <div class="chip">Revenue: {{formatCurrency($store_item->revenue)}}</div>
                            <div class="chip">Purchases: {{formatCurrency($store_item->purchase)}}</div>
                            <div class="chip">Profit: {{formatCurrency($store_item->profit)}}</div>
                            <div class="chip">Profit Margin: {{$store_item->profit_margin}}%</div>
                        </div>
                        <div class="ms-auto">
                            <a href="{{ route('store-items.edit', $store_item) }}" class="btn btn-sm btn-primary">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteItemModal">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

        <div class="card radius-10">

            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookings-data-table" class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Incoming</th>
                                <th>Outgoing</th>
                                <th>Balance</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyStats as $stat)
                            <tr>
                                <td>{{ $stat['date'] }}</td>
                                <td>{{ $stat['incoming'] }}</td>
                                <td>{{ $stat['outgoing'] }}</td>
                                <td><b>{{ $stat['balance'] }}</b></td>
                                <td>{{ $stat['sales'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <h6 class="text-center">Store Activities</h6>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bookings-data-table" class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Opening Stock</th>
                                <th scope="col">Description</th>
                                <th scope="col">Recipient</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Closing Stock</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($storeItemActivities as $activity)
                            <tr>
                                <th scope="row">{{$activity->activity_date}}</th>
                                <td>{{$activity->previous_qty}}</td>
                                <td>{{$activity->description}}</td>
                                <td>@if($activity->storeIssue){{$activity->storeIssue->recipient_name}}@endif</td>
                                <td>{{$activity->qty}}</td>
                                <td>{{$activity->current_qty}}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="#"><i class='bx bx-show'></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No activity during this period</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('rocker-theme.store-items.partials.delete-modal')
</div>
<script>
    window.addEventListener('load', function() {

    });
</script>