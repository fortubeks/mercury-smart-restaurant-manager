@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($user) ? 'Edit User' : 'Add User' }}</h5>
                <hr>
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($user))
                        @method('PUT')
                        @endif
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="input1" class="form-label">Full Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" id="input1"
                                placeholder="Full Name">
                            @error('name')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input2" class="form-label">Phone</label>
                            <input type="phone" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" id="input2"
                                placeholder="Phone">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input3" class="form-label">Email</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" id="input3"
                                placeholder="Email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input4" class="form-label">Photo</label>
                            <input type="file" name="photo" value="{{ old('photo') }}"
                                class="form-control @error('photo') is-invalid @enderror" id="input4">
                            @error('photo')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role Dropdown -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input5" class="form-label">Role</label>
                            <select name="role" id="input5" required
                                class="form-select @error('role') is-invalid @enderror">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                    {{ $role }}
                                </option>
                                @endforeach
                            </select>
                            @error('role')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Is Active Dropdown -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input6" class="form-label">Is Active</label>
                            <select name="is_active" id="input6" required
                                class="form-select @error('is_active') is-invalid @enderror">
                                <option value="">Select Status</option>
                                @foreach ($statusOptions as $key => $status)
                                <option value="{{ $key }}"
                                    {{ old('is_active') == $key ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                                @endforeach
                            </select>
                            @error('is_active')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input7" class="form-label">Address</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                class="form-control @error('address') is-invalid @enderror" id="input7"
                                placeholder="Address">
                            @error('address')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12" id="show_hide_password">
                            <label for="input8" class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" id="input8"
                                placeholder="Password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 pt-3">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
    <!--end row-->
</div>
@endsection