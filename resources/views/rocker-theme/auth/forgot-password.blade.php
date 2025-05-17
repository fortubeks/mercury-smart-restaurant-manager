@extends('rocker-theme.layouts.guest')
@section('contents')
<div class="section-authentication-cover">
    <div class="">
        <div class="row g-0">
            <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                    <div class="card-body">
                        <img src="assets/images/login-images/forgot-password-cover.svg" class="img-fluid" width="600" alt="" />
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                    <div class="card-body p-sm-5">
                        <div class="p-3">
                            <div class="text-center">
                                <img src="assets/images/icons/forgot-2.png" width="100" alt="" />
                            </div>
                            <h4 class="mt-5 font-weight-bold">Forgot Password?</h4>
                            <p class="text-muted">Enter your registered email ID to reset the password</p>
                            <div class="my-4">
                                <label class="form-label">Email id</label>
                                <input type="text" class="form-control" placeholder="example@user.com" />
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary">Send</button>
                                <a href="authentication-signin.html" class="btn btn-light"><i class='bx bx-arrow-back me-1'></i>Back to
                                    Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end row-->
    </div>
</div>
@endsection