@extends('dashboard.layouts.app')
@section('contents')
<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <form id="filterForm" method="GET" action="{{ route('bank-account-transactions.index') }}">
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
                <div class="row">
                    <div class="col">
                        <strong>Total Debits:</strong> <span class="text-danger">{{ formatCurrency($totalDebits) }}</span> ({{ $countDebits }} transactions)
                    </div>
                    <div class="col">
                        <strong>Total Credits</strong> <span class="text-success">{{ formatCurrency($totalCredits) }}</span> ({{ $countCredits }} transactions)
                    </div>
                </div>
            </form>
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

                    @if ($transactions->count())
                    <tbody>
                        @foreach ($transactions as $transaction)
                        @php
                        $isOutgoing = $transaction->transaction_type === 'debit';
                        $amountClass = $isOutgoing ? 'text-danger' : 'text-success';
                        @endphp
                        <tr>
                            <td>{{ $transaction->transaction_date }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->bankAccount ? $transaction->bankAccount->account_name : ''}}</td>
                            <td class="{{ $amountClass }}">{{ formatCurrency($transaction->amount) }}</td>
                            <td>
                                <div class="d-flex align-items-center order-actions">
                                    <x-bank-account-transaction-link :transaction="$transaction" />

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <tbody>
                        <tr>
                            <td colspan="7">
                                <h5>No transactions. Create one now.</h5>
                            </td>
                        </tr>
                    </tbody>
                    @endif
                </table>
            </div>
            <div class="pagination mt-3 d-flex justify-content-center">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
<!--end row-->

<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection