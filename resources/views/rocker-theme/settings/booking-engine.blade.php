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
    <form action="{{ route('settings.update-booking-engine-settings') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">

                        <div class="row">
                            <div class="card-header mb-4">
                                <h4>Booking Engine Settings</h4>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <label for="input1" class="form-label">Payment Key</label>
                                <div class="input-group" id="show_hide_password">
                                    <input type="password" class="form-control border-end-0" name="sk" placeholder="Payment Key"> <a
                                        href="javascript:;" class="input-group-text bg-transparent"><i
                                            class='bx bx-hide'></i></a>

                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-3">
                                <label for="input1" class="form-label">Booking Policy</label>
                                <textarea name="booking_policy" class="form-control">{{hotel()->booking_policy}}</textarea>
                            </div>

                        </div>
                        <div class="row">
                            <div class="card-header mb-4">
                                <h4>Reservation Settings</h4>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <label for="input2" class="form-label">Reservation Payment Advice</label>
                                <textarea name="reservation_payment_advice" placeholder="Transfer Instruction eg. Bank details" class="form-control">{{hotel()->appSetting->reservation_payment_advice}}</textarea>
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
<script>
    window.addEventListener('load', function() {
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>