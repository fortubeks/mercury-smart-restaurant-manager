@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->


        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="card-title">{{ isset($tax) ? 'Edit Tax' : 'Add New Tax' }}</h5>
                        <hr>
                        <!--include flash message manually if you wish -->
                        <form action="{{ isset($tax) ? route('taxes.update', $tax->id) : route('taxes.store') }}" method="POST">
                            @csrf
                            @if(isset($tax))
                            @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                    <label for="input1" class="form-label">Name</label>
                                    <input type="text" name="name" required value="{{ old('name', isset($tax) ? $tax->name : '') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="input1"
                                        placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                    <label for="input1" class="form-label">Rate</label>
                                    <input type="number" step="0.1" name="rate" required value="{{ old('rate', isset($tax) ? $tax->rate : '') }}"
                                        class="form-control @error('rate') is-invalid @enderror" id="input1"
                                        placeholder="Rate (%)">
                                    @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" value="1" type="checkbox" name="is_active" role="switch" id="flexSwitchCheckDefault1"
                                            {{ old('is_active', isset($tax) && $tax->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexSwitchCheckDefault1">Status</label>
                                    </div>
                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 pt-3">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>