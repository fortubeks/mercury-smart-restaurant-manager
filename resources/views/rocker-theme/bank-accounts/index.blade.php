@extends('rocker-theme.layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <h4>Bank Accounts</h4>
            </div>
            <div class="ms-auto">
                <a href="{{ route('bank-account-transactions.index') }}" class="btn btn-sm btn-dark"><i class="bx bx-spreadsheet mr-2"></i>View Bank Transactions</a>
                <a href="{{ route('bank-account-transactions.create') }}" class="btn btn-sm btn-dark"><i class="bx bx-plus mr-2"></i>New Transaction</a>
                <a href="{{ route('bank-accounts.create') }}" class="btn btn-sm btn-dark"><i class="bx bx-plus mr-2"></i>New Bank Account</a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="accounts-table" class="table">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>Bank Name</th>
                        <th>Account Number</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if ($bankAccounts->count())
                <tbody>
                    @foreach ($bankAccounts as $bankAccount)
                    <tr>
                        <td>{{ $bankAccount->account_name }}</td>
                        <td>{{ $bankAccount->bank_name }}</td>
                        <td>{{ $bankAccount->account_number}}</td>
                        <td>{{ formatCurrency($bankAccount->balance) }}</td>
                        <td>
                            <div class="d-flex align-items-center order-actions">
                                <a href="{{ url('bank-account-transactions?bank_account_id='. $bankAccount->id) }}" title="Transactions"><i class='bx bx-spreadsheet'></i></a>
                                <a href="{{ route('bank-accounts.show', $bankAccount->id) }}" title="Edit" class="ms-3"><i class='bx bx-pencil'></i></a>
                                <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$bankAccount->id}}" data-resource-url="{{url('bank-accounts')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <tbody>
                    <tr>
                        <td colspan="7">
                            <h5>No bank account. Create one now.</h5>
                        </td>
                    </tr>
                </tbody>
                @endif
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