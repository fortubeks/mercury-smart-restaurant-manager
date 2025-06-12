@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($bankAccount) ? 'Edit Bank Account' : 'Add New Bank Account' }}</h5>
                <hr>
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($bankAccount) ? route('bank-accounts.update', $bankAccount->id) : route('bank-accounts.store') }}">
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