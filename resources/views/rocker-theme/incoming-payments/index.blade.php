@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->


        <div class="card">
            <div class="card-body">
                <form id="filterForm" method="GET" action="{{ route('incoming-payments.index') }}">
                    <div class="d-flex mb-3">
                        <div class="col-md-3">
                            <label for="bank_account_id">Bank Account</label>
                            <select id="bank_account_id" class="form-select form-control" name="bank_account_id">
                                <option value="">--All Bank Accounts--</option>
                                @foreach (getModelList('bank-accounts') as $bankAccount)
                                <option value="{{ $bankAccount->id }}"
                                    {{ request('bank_account_id') == $bankAccount->id ? 'selected' : '' }}>
                                    {{ $bankAccount->account_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 px-4">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input class="form-control date-format" data-toggle="flatpickr" type="date"
                                    id="search_start" name="start_date" value="{{ request('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3 px-4">
                            <div class="form-group">
                                <label>End Date</label>
                                <input class="form-control date-format" data-toggle="flatpickr" type="date"
                                    id="search_end" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-3 px-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-3 w-100">
                                    <i class="bx bx-search-alt me-0"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Total Payments: <span id="total_payments">{{ number_format($totalIncomingPayments, 2) }}</span></h5>
                        </div>
                        <h5>Payment Methods</h5>
                        <table class="table">
                            <tr>
                                <th>Payment Method</th>
                                <th>Total</th>
                            </tr>
                            @foreach($sumByIncomingPaymentMethod as $method)
                            <tr>
                                <td>{{ ucfirst($method->payment_method) }}</td>
                                <td>{{ formatCurrency($method->total) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="col-md-4">
                        <h5>Bank Accounts</h5>
                        <table class="table">
                            <tr>
                                <th>Bank</th>
                                <th>Total</th>
                            </tr>
                            @foreach($sumByBank as $bank)
                            <tr>
                                <td>{{ $bank->bank_name ? $bank->bank_name : 'Unknown' }}</td>
                                <td>{{ formatCurrency($bank->total) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h5>Departments</h5>
                        <table class="table">
                            <tr>
                                <th>Name</th>
                                <th>Total</th>
                            </tr>
                            @foreach($sumByPayableType as $payable)
                            <tr>
                                <td>{{ class_basename($payable->payable_type) }}</td>
                                <td>{{ formatCurrency($payable->total) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="payments-table" class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Bank Account</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        @if ($incomingPayments->count())
                        <tbody>
                            @foreach ($incomingPayments as $incomingPayment)
                            <tr>
                                <td>{{ formatDate($incomingPayment->date_of_payment) }}</td>
                                <td>{{ $incomingPayment->payable ? class_basename($incomingPayment->payable) . ' #' . $incomingPayment->payable->id : '' }}</td>
                                <td>{{ $incomingPayment->bankAccount ? $incomingPayment->bankAccount->account_name : ''}}</td>
                                <td><span class="text-success">{{ formatCurrency($incomingPayment->amount) }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center order-actions">
                                        <x-payment-link :payment="$incomingPayment" />
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="7">
                                    <h5>No incoming payments. Create one now.</h5>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
                <div class="pagination mt-3 d-flex justify-content-center">
                    {{ $incomingPayments->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
<div class="modal fade" id="deleteGuestModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this payment? Any associating payment record will be affected
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" id="account-form" action="{{ url('bank-accounts/') }}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>