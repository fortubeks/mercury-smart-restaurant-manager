@extends('rocker-theme.layouts.guest')
@section('contents')
<div class="section-authentication-cover">
    <div class="">
        <div class="row g-0">

            <div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                    <div class="card-body">
                        <img src="assets/images/login-images/register-cover.svg" class="img-fluid auth-img-cover-login" width="550" alt="" />
                    </div>
                </div>

            </div>

            <div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                    <div class="card-body p-sm-5">
                        <div class="">
                            <div class="mb-3 text-center">
                                <img src="assets/images/logo.png" width="60" alt="" />
                            </div>
                            <div class="text-center mb-4">
                                <h5 class="">{{env('APP_NAME')}}</h5>
                                <p class="mb-0">Please fill the below details to create your account</p>
                            </div>
                            <div class="list-inline contacts-social text-center">

                                <a href="{{ route('google.login') }}"
                                    class="list-inline-item bg-google w-100 text-white border-0 rounded-3"><i
                                        class="bx bxl-google"></i> Sign Up With Google</a>

                            </div>
                            <div class="login-separater text-center mb-5"> <span>OR SIGN UP WITH EMAIL</span>
                                <hr />
                            </div>
                            <div class="form-body">
                                <form action="{{route('register')}}" method="POST" class="row g-3">
                                    @csrf
                                    <div class="col-12">
                                        <label for="inputUsername" class="form-label">First Name</label>
                                        <input type="text" name="first_name" value="{{old('first_name')}}" required
                                            autocomplete="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="first_name" placeholder="Enter your First Name">
                                        @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="inputUsername" class="form-label">Last Name</label>
                                        <input type="text" name="last_name" value="{{old('last_name')}}" required
                                            autocomplete="last_name"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" placeholder="Enter your Last Name">
                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="inputEmailAddress" class="form-label">Email Address</label>
                                        <input type="email" name="email" value="{{old('email')}}" required
                                            autocomplete="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="inputEmailAddress" placeholder="Enter your email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="inputUsername" class="form-label">Phone</label>
                                        <input type="text" name="phone" value="{{old('phone')}}" required
                                            autocomplete="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" placeholder="Phone number for OTP">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label">Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" name="password"
                                                class="form-control border-end-0 @error('password') is-invalid @enderror"
                                                id="inputChoosePassword"
                                                placeholder="Enter Password"> <a href="javascript:;"
                                                class="input-group-text bg-transparent"><i
                                                    class='bx bx-hide'></i></a>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label for="inputChoosePassword" class="form-label">Confirm
                                            Password</label>
                                        <div class="input-group" id="show_hide_password">
                                            <input type="password" name="password_confirmation"
                                                class="form-control border-end-0" id="inputChoosePassword"
                                                placeholder="Confirm Password"> <a
                                                href="javascript:;" class="input-group-text bg-transparent"><i
                                                    class='bx bx-hide'></i></a>
                                        </div>
                                    </div>

                                    {{-- <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckChecked">
                                                    <label class="form-check-label" for="flexSwitchCheckChecked">I
                                                        read
                                                        and agree to Terms & Conditions</label>
                                                </div>
                                            </div> --}}
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <p class="d-none"><input type="text" name="myfield" value=""></p>
                                            <button type="submit" class="btn btn-primary">Sign up</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-center ">
                                            <p class="mb-0">Already have an account? <a
                                                    href="{{ route('login') }}">Sign in here</a></p>
                                        </div>
                                    </div>
                                </form>
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