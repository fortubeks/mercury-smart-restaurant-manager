@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row mb-5">
            <div class="col-xl-12 mx-auto">
                <!--include flash message manually if you wish -->
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>Supplier Details</h4>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" required value="{{ old('name', isset($supplier) ? $supplier->name : '') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="contact_person_name" class="form-label">Contact Person Name</label>
                                    <input type="text" name="contact_person_name" required
                                        value="{{ old('contact_person_name', isset($supplier) ? $supplier->contact_person_name : '') }}"
                                        class="form-control @error('contact_person_name') is-invalid @enderror"
                                        id="contact_person_name" placeholder="Contact Person Name">
                                    @error('contact_person_name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="contact_person_phone" class="form-label">Contact Person Phone</label>
                                    <input type="text" name="contact_person_phone" required
                                        value="{{ old('contact_person_phone', isset($supplier) ? $supplier->contact_person_phone : '') }}"
                                        class="form-control @error('contact_person_phone') is-invalid @enderror"
                                        id="contact_person_phone" placeholder="Contact Person Phone">
                                    @error('contact_person_phone')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', isset($supplier) ? $supplier->email : '') }}"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        placeholder="Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" name="address" value="{{ old('address', isset($supplier) ? $supplier->address : '') }}"
                                        class="form-control @error('address') is-invalid @enderror" id="address"
                                        placeholder="Address">
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>Bank Details</h4>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="bank_account_no" class="form-label">Bank Account Number</label>
                                    <input type="text" name="bank_account_no" value="{{ old('bank_account_no', isset($supplier) ? $supplier->bank_account_no : '') }}"
                                        class="form-control @error('bank_account_no') is-invalid @enderror"
                                        id="bank_account_no" placeholder="Bank Account Number">
                                    @error('bank_account_no')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" value="{{ old('bank_name', isset($supplier) ? $supplier->bank_name : '') }}"
                                        class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                                        placeholder="Bank Name">
                                    @error('bank_name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <label for="bank_account_name" class="form-label">Bank Account Name</label>
                                    <input type="text" name="bank_account_name"
                                        value="{{ old('bank_account_name', isset($supplier) ? $supplier->bank_account_name : '') }}"
                                        class="form-control @error('bank_account_name') is-invalid @enderror"
                                        id="bank_account_name" placeholder="Bank Account Name">
                                    @error('bank_account_name')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($supplier) ? 'Update Supplier' : 'Create Supplier' }}
                        </button>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>