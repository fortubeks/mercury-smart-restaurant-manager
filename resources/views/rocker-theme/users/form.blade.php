@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($user) ? 'Edit User' : 'Add User' }}</h5>
                <hr class="mb-3">
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($user))
                        @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input1" class="form-label">Full Name</label>
                                        <input type="text" name="name" required value="{{ old('name', isset($user) ? $user->name : '') }}" class="form-control @error('name') is-invalid @enderror" id="input1" placeholder="Full Name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input3" class="form-label">Phone</label>
                                        <input type="phone" name="phone" value="{{ old('phone', isset($user) ? $user->phone : '') }}" class="form-control @error('phone') is-invalid @enderror" id="input3" placeholder="Phone">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 ">
                                        <label for="input4" class="form-label">Address</label>
                                        <input type="text" name="address" value="{{ old('address', isset($user) ? $user->address : '') }}" class="form-control @error('address') is-invalid @enderror" id="input4">
                                        @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input4" class="form-label">Photo</label>
                                        <input type="file" name="profile_image" class="form-control @error('profile_image') is-invalid @enderror" id="input4">
                                        @error('profile_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if(isset($user))
                                        @if($user->profile_image)
                                        <div>
                                            <span>Current Photo:</span>
                                            <img src="{{url('storage/images/profile_images/' . $user->profile_image)}}" alt="User Photo" style="max-width: 100px;">
                                            <input type="hidden" name="old_photo" value="{{ $user->profile_image }}">
                                        </div>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input4" class="form-label">Role</label>
                                        <select name="role_id" id="role" class="form-select" @if(isset($user) && $user->is_super_admin && auth()->id() == $user->id) disabled @endif>
                                            <option value="">Select Role</option>
                                            @foreach (getModelList('roles') as $role)
                                            @if($role->id != 1)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id', isset($user) ? $user->role_id : '') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input4" class="form-label">Is Active</label>
                                        <select name="is_active" id="is_active" required class="form-select @error('is_active') is-invalid @enderror">
                                            <option value="">Select Status</option>
                                            @foreach (getActiveOptions() as $key => $status)
                                            <option value="{{ $key }}" {{ old('is_active', isset($user) ? $user->is_active : '') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('is_active')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <hr>
                                    <p class="mb-4">Login Details For User</p>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input4" class="form-label">Email</label>
                                        <input type="email" name="email" required value="{{ old('email', isset($user) ? $user->email : '') }}" class="form-control @error('email') is-invalid @enderror" id="input4" placeholder="Email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 ">
                                        <label for="input4" class="form-label">Password</label>
                                        <input type="text" name="password" class="form-control @error('password') is-invalid @enderror" id="input4">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 pt-3">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <h6>Select Restaurant</h6>
                                <div class="align-items-center gap-3 mb-3">
                                    <select name="restaurant_id" class="form-select">
                                        @foreach (restaurants() as $restaurant)
                                        <option value="{{ $restaurant->id }}" {{ old('restaurant_id', isset($user) ? $user->restaurant_id : '') == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <h6>Select Roles & Permissions</h6>
                                <div class="align-items-center gap-3">
                                    @if(isset($user))
                                    @foreach(getModelList('roles') as $role)
                                    @if($role->id != 1)
                                    <div class="form-check form-check-success">
                                        <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" name="role_ids[]" value="{{ $role->id }}" {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    @foreach(getModelList('roles') as $role)
                                    @if($role->id != 1)
                                    <div class="form-check form-check-success">
                                        <input class="form-check-input" type="checkbox" id="role_{{ $role->id }}" name="role_ids[]" value="{{ $role->id }}">
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>