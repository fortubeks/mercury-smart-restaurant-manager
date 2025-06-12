@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-6 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="card-header mb-3">
                            <h5>Restaurant Information</h4>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>View or Edit Your Information</span>
                            <a href="{{route('settings.restaurant.edit')}}" class="btn btn-primary btn-sm">View</a>
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
                            <a href="{{route('taxes.index')}}" class="btn btn-secondary btn-sm">Manage Now</a>
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
        </div>
    </div>
</div>