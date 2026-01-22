@extends('rocker-theme.layouts.app')

<div class="page-wrapper">

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dashboard</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Purchases</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('purchases.summary') }}" class="btn btn-dark">View Summary</a>
                <a href="{{ route('purchases.create') }}" class="btn btn-dark">Add New</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-around text-center mb-3">
                    <div class="col-12">
                        <h6> Unpaid Purchases (<a href="{{ route('purchases.index') }}">View Dashbaord</a>) (<a href="{{ route('purchases.summary') }}">View Summary</a>)</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="purchases-data-table" class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Purchase Status</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($purchases->count())
                        <tbody>
                            @foreach ($purchases as $purchase)
                            @php
                            $purchaseStatus = $purchase->status;
                            $purchaseStatusColor = '';
                            if ($purchaseStatus == 'Received') {
                            $purchaseStatusColor = 'text-success';
                            }
                            if ($purchaseStatus == 'Partial') {
                            $purchaseStatusColor = 'text-danger';
                            }
                            if ($purchaseStatus == 'Ordered') {
                            $purchaseStatusColor = 'text-primary';
                            }
                            if ($purchaseStatus == 'Pending') {
                            $purchaseStatusColor = 'text-warning';
                            }
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $purchase->purchase_date)->format('jS, M Y') }}</td>
                                <td style="text-wrap: wrap;">{{ $purchase->getItems() }}</td>
                                <td class="text-right">{{ formatCurrency($purchase->amount) ?? '' }}</td>
                                <td class="{{ $purchaseStatusColor }}">{{ $purchase->status }}</td>
                                <td>{{ $purchase->paymentStatus() }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a title="Show/Edit" href="{{ route('purchases.show', $purchase) }}"><i class='bx bx-show'></i></a>
                                        <a href="javascript:void(0);" class="ms-3" onclick="setId(this)" data-amount="{{ $purchase->amount }}" data-id="{{ $purchase->id }}"
                                            title="Add payment" data-bs-toggle="modal" data-bs-target="#modal-payment"><i class="fadeIn animated bx bx-money"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <h4>No Available Purchase</h4>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
<div id="modal-payment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-standard-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-standard-title">Add Payment to Purchase [<span id="purchase_amount"></span>]</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> <!-- // END .modal-header -->
            <form action="{{url('outgoing-payments')}}" method="POST">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Amount') }}</label>
                            <input type="number" class="form-control" name="amount" value="0" required>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Mode of payment') }}</label>
                            <select name="mode_of_payment" class="form-select form-control">
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                                <option value="pos">POS</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="phone">Bank Account</label>
                            <select class="form-select" name="bank_account_id">
                                <option value="">--Select--</option>
                                @foreach (getModelList('bank-accounts') as $bankAccount)
                                <option value="{{$bankAccount->id}}">{{ $bankAccount->account_name. "(".formatCurrency($bankAccount->balance).")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Date of payment') }}</label>
                            <input type="date" class="form-control datepicker flatpickr-input active" name="date_of_payment"
                                data-toggle="flatpickr" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-12 mb-2">
                            <label>{{ __('Note') }}</label>
                            <input type="text" class="form-control" name="note">
                        </div>
                    </div>
                </div><!-- // END .modal-body -->
                <div class="modal-footer">
                    <input type="hidden" id="purchase_id" value="" name="purchase_id">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div><!-- // END .modal-footer -->
            </form>
        </div> <!-- // END .modal-content -->
    </div> <!-- // END .modal-dialog -->
</div> <!-- // END .modal -->
<script>
    window.addEventListener('load', function() {

        var purchases_table = $('#purchases-data-table').DataTable({
            lengthChange: true,
            buttons: ['excel', 'pdf', 'print'],
            sort: false
        });

        purchases_table.buttons().container().appendTo('#purchases-data-table_wrapper .col-md-6:eq(0)');

        $('input').click(function() {
            this.select();
        });
    });

    function setId(item) {
        var id = item.dataset.id;
        var amount = item.dataset.amount;
        document.getElementById('purchase_id').value = id;
        document.getElementById('purchase_amount').innerHTML = amount;
    }
</script>