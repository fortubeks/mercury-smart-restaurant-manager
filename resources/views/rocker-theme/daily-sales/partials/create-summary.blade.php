<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#summary1" role="tab" aria-selected="true">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-bed font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Orders</div>
                    </div>
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#summary8" role="tab" aria-selected="false" tabindex="-1">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Settlements</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#summary9" role="tab" aria-selected="false" tabindex="-1">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Debtors</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#summary10" role="tab" aria-selected="false" tabindex="-1">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Creditors</div>
                    </div>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#summary11" role="tab" aria-selected="false" tabindex="-1">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-money font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Outflows</div>
                    </div>
                </a>
            </li>
        </ul>
        <div class="tab-content py-3">
            <div class="tab-pane fade active show" id="summary1" role="tabpanel">
                <!--Restaurant-->
                <h3>Restaurant</h3>
                <!--Guest, room-no, amount, payment-method-->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="restaurant-data-table" class="table ">
                                <thead>
                                    <tr>
                                        <th>Items</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{$order->items_string }}</td>
                                        <td>@if ($order->customer)
                                            <a href="{{route('customers.show',$order->customer->id)}}">{{ $order->customer->name() }}</a>
                                            @else
                                            Walkin Customer
                                            @endif
                                        </td>
                                        <td>{{formatCurrency($order->total_amount)}}</td>
                                        <td>{{$order->payment_details}}</td>
                                        <td><a href="{{ route('orders.show', $order->id) }}" class="me-3"><i class='bx bx-show'></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="summary8" role="tabpanel">
                <h3>Settlement</h3>
                <!--Guest, room-no, amount, payment-method-->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="creditors-data-table" class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Guest Name</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    No Record

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="summary9" role="tabpanel">
                <h3>Debtors</h3>
                <!--Guest, room-no, amount, payment-method-->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="credit-data-table" class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Guest Name</th>
                                        <th>Last Visit</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    No Record
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="summary10" role="tabpanel">
                <h3>Creditors</h3>
                <!--Guest, room-no, amount, payment-method-->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="creditors-data-table" class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Guest Name</th>
                                        <th>Method</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    No Record
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="summary11" role="tabpanel">
                <h3>Outflows</h3>
                <!--Guest, room-no, amount, payment-method-->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="creditors-data-table" class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($outgoingPayments as $outgoingPayment)
                                    <tr>
                                        @if(get_class($outgoingPayment->payable) == 'App\Models\PurchasePayment')
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $outgoingPayment->payable->purchase->purchase_date)->format('jS, M Y') }}</td>
                                        <td>{{ $outgoingPayment->payable->purchase->getItems() }}</td>
                                        <td class="text-right">{{ formatCurrency($outgoingPayment->payable->purchase->amount) ?? '' }}</td>
                                        @elseif(get_class($outgoingPayment->payable) == 'App\Models\ExpensePayment')
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $outgoingPayment->payable->expense->expense_date)->format('jS, M Y') }}</td>
                                        <td>{{ $outgoingPayment->payable->expense->getItems() ?? '' }}</td>
                                        <td class="text-right">{{ formatCurrency($outgoingPayment->payable->expense->amount) ?? '' }}</td>
                                        @endif
                                        <td><a href="#" class="me-3"><i class='bx bx-show'></i></a></td>
                                    </tr>
                                    @empty
                                    No Record
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>