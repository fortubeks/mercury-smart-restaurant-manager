@extends('rocker-theme.layouts.app')
@section('content')

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Cash to Bank <small> [Cash balance: {{formatCurrency($cashAtHand)}}]</small></h5>
                </div>
                <form action="{{ route('bank-account-transactions.store') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 row-cols-lg-auto align-items-center">
                        <div class="col">
                            <select class="form-select" id="bank_account_id" name="bank_account_id" required>
                                <option value="">--Select Bank--</option>
                                @foreach($bankAccounts as $bankAccount)
                                @if(!$bankAccount->isDefaultCashAccount())
                                <option value="{{ $bankAccount->id }}">{{ $bankAccount->account_name. "(".$bankAccount->balance.")" }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input class="form-control" type="number" name="amount" placeholder="Amount to send" required max="{{ $cashAtHand }}">
                        </div>
                        <div class="col">
                            <input class="form-control date-format" type="date" name="transaction_date" value="{{now()}}" required>
                        </div>
                        <div class="col">
                            <textarea class="form-control" name="description" placeholder="Description"></textarea>
                        </div>
                        <div class="col">
                            <button type="submit" name="submit_action" value="cash_to_bank" class="btn btn-primary px-4">Send</button>
                        </div>
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Bank to Bank </h5>
                </div>
                <form action="{{ route('bank-account-transactions.store') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 row-cols-lg-auto align-items-center">
                        <div class="col">
                            <select class="form-select" name="from_bank_account_id" required>
                                <option value="">--Select Bank--</option>
                                @foreach($bankAccounts as $bankAccount)
                                <option value="{{ $bankAccount->id }}">{{ $bankAccount->account_name. "(".formatCurrency($bankAccount->balance).")" }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input class="form-control" type="number" name="amount" placeholder="Amount to send" required>
                        </div>
                        <div class="col">
                            <select class="form-select" name="to_bank_account_id" required>
                                <option value="">--Select Bank--</option>
                                @foreach($bankAccounts as $bankAccount)
                                <option value="{{ $bankAccount->id }}">{{ $bankAccount->account_name. "(".formatCurrency($bankAccount->balance).")" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <input class="form-control date-format" type="date" name="transaction_date" value="{{now()}}" required>
                        </div>
                        <div class="col">
                            <textarea class="form-control" name="description" placeholder="Description"></textarea>
                        </div>
                        <div class="col">
                            <button type="submit" name="submit_action" value="bank_to_bank" class="btn btn-primary px-4">Transfer</button>
                        </div>
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h5 class="mb-0">Bank Withdrawal </h5>
                </div>
                <form action="{{ route('bank-account-transactions.store') }}" method="POST">
                    @csrf
                    <div class="row row-cols-1 g-3 row-cols-lg-auto align-items-center">
                        <div class="col">
                            <select class="form-select" name="bank_account_id" id="bankAccountSelect" required>
                                <option value="">--Select Bank--</option>
                                @foreach($bankAccounts as $bankAccount)
                                <option value="{{ $bankAccount->id }}"
                                    data-balance="{{ $bankAccount->balance }}">
                                    {{ $bankAccount->account_name . " (" . formatCurrency($bankAccount->balance) . ")" }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <input class="form-control" type="number" name="amount" id="amountInput" placeholder="Amount to withdraw" required max="">
                        </div>
                        <div class="col">
                            <input class="form-control date-format" type="date" name="transaction_date" value="{{now()}}" required>
                        </div>
                        <div class="col">
                            <textarea class="form-control" name="description" placeholder="Description"></textarea>
                        </div>
                        <div class="col">
                            <button type="submit" name="submit_action" value="withdraw" class="btn btn-primary px-4">Withdraw</button>
                        </div>
                    </div><!--end row-->
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('load', function() {
        const bankSelect = document.getElementById('bankAccountSelect');
        const amountInput = document.getElementById('amountInput');

        bankSelect.addEventListener('change', function() {
            const selectedOption = bankSelect.options[bankSelect.selectedIndex];
            const balance = selectedOption.getAttribute('data-balance');

            if (balance) {
                amountInput.setAttribute('max', balance);
            } else {
                amountInput.removeAttribute('max');
            }
        });
    })
</script>
@endsection