@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Restaurant</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Update</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary">Settings</button>
                    <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
                        <a class="dropdown-item" href="javascript:;">Another action</a>
                        <a class="dropdown-item" href="javascript:;">Something else here</a>
                        <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                    </div>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <form action="{{ route('restaurant.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card">
                                <div class="card-body p-4">

                                    <div class="row">
                                        <div class="card-header mb-4">
                                            <h4>Basic Information</h4>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Restaurant Name</label>
                                            <input type="text" name="name" required value="{{ old('name', $restaurant->name) }}"
                                                class="form-control @error('name') is-invalid @enderror" id="input1"
                                                placeholder="Restaurant Name">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Restaurant Type</label>
                                            <select name="type" id="type"
                                                class="form-select @error('type') is-invalid @enderror">
                                                <option value="">Select restaurant Type</option>
                                                @foreach (getModelList('restaurant-types') as $key => $type)
                                                <option value="{{ $key }}"
                                                    {{ $key == $restaurant->type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Country</label>
                                            <select name="country_id" id="country_id"
                                                class="form-select @error('country') is-invalid @enderror">
                                                <option value="" disabled>Select Country</option>
                                                @foreach (getModelList('countries') as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ $country->id == 161 ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">State</label>
                                            <select name="state_id" id=""
                                                class="form-select @error('country') is-invalid @enderror">
                                                <option value="" disabled>Select State</option>
                                                @foreach (getModelList('states') as $key => $state)
                                                <option value="{{ $state->id }}"
                                                    {{ $state->id == $restaurant->state_id ? 'selected' : '' }}>{{ $state->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('state')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Address</label>
                                            <input type="text" name="address" required
                                                value="{{ old('address', $restaurant->address) }}"
                                                class="form-control @error('address') is-invalid @enderror" id="input1"
                                                placeholder="Address">
                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                <img src="{{ url('storage/'. $restaurant->logo) }}" alt="restaurant logo"
                                                    class="rounded-circle p-1 bg-primary" width="110">
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Logo</label>
                                            <input type="file" name="logo" value="{{ old('logo', $restaurant->logo) }}"
                                                class="form-control @error('logo') is-invalid @enderror" id="input1"
                                                placeholder="Logo">
                                            @error('logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Website URL</label>
                                            <input type="text" name="website" value="{{ old('website', $restaurant->website) }}"
                                                class="form-control @error('website') is-invalid @enderror" id="input1"
                                                placeholder="Website">
                                            @error('website')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                                            <label for="input1" class="form-label">Phone</label>
                                            <input type="number" name="phone" required
                                                value="{{ old('phone', $restaurant->phone) }}"
                                                class="form-control @error('phone') is-invalid @enderror" id="input1"
                                                placeholder="Phone">
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="">
                                            <button class="btn btn-primary ">Save</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->