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
    <form action="{{ route('settings.update-app-settings') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">

                        <div class="row">
                            <div class="card-header mb-4">
                                <h4>App Settings</h4>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="restaurant_manage_stock" role="switch" id="flexSwitchCheckDefault1" {{ $appSetting->restaurant_manage_stock == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault1">Manage Restaurant Inventory</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="auto_checkin" role="switch" id="flexSwitchCheckDefault2" {{ $appSetting->auto_checkin == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault2">Allow Automatic Check In</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="include_tax" role="switch" id="flexSwitchCheckDefault3" {{ $appSetting->include_tax == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault3">Include Tax in Bill</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="allow_caution_fee" role="switch" id="flexSwitchCheckDefault4" {{ $appSetting->allow_caution_fee == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault4">Allow Caution Fee</label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="card-header mb-4">
                                <h4>Module Settings</h4>
                                <p>Keep the modules you need</p>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="venue_active" role="switch" id="flexSwitchCheckDefault5" {{ $appSetting->venue_active == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault5">Venue</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="laundry_active" role="switch" id="flexSwitchCheckDefault6" {{ $appSetting->laundry_active == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault6">Laundry</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="gym_active" role="switch" id="flexSwitchCheckDefault7" {{ $appSetting->gym_active == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault7">Gym</label>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="swimming_active" role="switch" id="flexSwitchCheckDefault8" {{ $appSetting->swimming_active == 1 ? 'checked' : ''}}>
                                    <label class="form-check-label" for="flexSwitchCheckDefault8">Swimming</label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
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