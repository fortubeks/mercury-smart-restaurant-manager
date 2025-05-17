@extends('dashboard.layouts.app')

<style>
    .user-photo {
        width: 40px;
        height: auto;
    }
</style>

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
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('rooms.index') }}" class="btn btn-dark">View Hotel</a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <div class="card-header mb-3">
                        <h5>Property Information</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>View or Edit Your Information</span>
                        <a href="{{route('settings.hotel.information')}}" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <div class="card-header mb-3">
                        <h5>Manage Tax</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Manage all your taxes here</span>
                        <a href="{{route('settings.taxs.index')}}" class="btn btn-secondary btn-sm">Manage Now</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6 ">
            <div class="card">
                <div class="card-body p-4">
                    <div class="card-header mb-3">
                        <h5>App Settings</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>View or Edit Your App Settings</span>
                        <a href="{{route('settings.app.settings')}}" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 ">
            <div class="card">
                <div class="card-body p-4">
                    <div class="card-header mb-3">
                        <h5>Bulk SMS Settings</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>View or Edit Your Bulk SMS Settings</span>
                        <a href="{{route('settings.bulk-sms')}}" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-xl-6 ">
            <div class="card">
                <div class="card-body p-4">
                    <div class="card-header mb-3">
                        <h5>Booking & Reservation</h4>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>View or Edit Settings For Your Booking Engine & Reservation</span>
                        <a href="{{route('settings.booking-engine')}}" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!--end row-->
</div>
@endsection