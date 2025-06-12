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
                    <li class="breadcrumb-item active" aria-current="page">{{ isset($bankAccount) ? 'Edit Account' : 'Create Account' }}</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('bank-accounts.index') }}" class="btn btn-dark">View Accounts</a>

        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <!--include flash message manually if you wish -->
                    <form action="{{ isset($bankAccount) ? route('bank-accounts.update', $bankAccount->id) : route('bank-accounts.store') }} " method="POST">
                        @csrf
                        @if(isset($bankAccount))
                        @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pb-3">
                                <label for="input1" class="form-label">Account Name</label>
                                <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name', $bankAccount->account_name ?? '') }}" required>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pb-3">
                                <label for="input1" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name', $bankAccount->bank_name ?? '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pb-3">
                                <label for="input1" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number', $bankAccount->account_number ?? '') }}">
                            </div>
                            @if(!isset($bankAccount))
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pb-3">
                                <label for="input1" class="form-label">Initial Balance</label>
                                <input type="number" class="form-control" id="balance" name="balance" value="0">
                            </div>
                            @endif
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end pt-3">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
</div>
@endsection