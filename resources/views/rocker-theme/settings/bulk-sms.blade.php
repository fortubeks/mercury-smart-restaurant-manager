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
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
            
        </div>
        <!--end breadcrumb-->
        <!--include flash message manually if you wish -->
        <form action="{{ route('settings.update-bulk-sms-settings') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">

                            <div class="row">
                                <div class="card-header mb-4">
                                    <h4>Bulk SMS Settings</h4>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label for="input1" class="form-label">EbulkSMS Username</label>
                                    <input type="text" name="sms_api_username" required value="{{ $appSetting->sms_api_username }}" class="form-control @error('sms_api_username') is-invalid @enderror" placeholder="EBulk SMS API Username">
                                    @error('sms_api_username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label for="input1" class="form-label">EbulkSMS API Key</label>
                                    <input type="text" name="sms_api_key" required value="{{ $appSetting->sms_api_key }}" class="form-control @error('sms_api_key') is-invalid @enderror" placeholder="EBulk SMS API Key">
                                    @error('sms_api_key')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                    <label for="input1" class="form-label">Sender Name</label>
                                    <input type="text" name="sms_api_sender" maxlength="10" required value="{{ $appSetting->sms_api_sender }}" class="form-control @error('sms_api_sender') is-invalid @enderror" placeholder="EBulk SMS Sender">
                                    @error('sms_api_sender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button class="btn btn-primary btn-sm">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </form>
    </div>
    <!--end row-->
    </div>
@endsection
