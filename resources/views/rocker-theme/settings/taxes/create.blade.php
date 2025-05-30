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
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('settings.taxs.index') }}" class="btn btn-dark">View Tax(s)</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <!--include flash message manually if you wish -->
                        <form action="{{ route('settings.taxs.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3 ">
                                    <label for="input1" class="form-label">Name</label>
                                    <input type="text" name="name" required value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror" id="input1"
                                        placeholder="Name">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                              
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3 ">
                                    <label for="input1" class="form-label">Rate</label>
                                    <input type="text" name="rate" required value="{{ old('rate') }}"
                                        class="form-control @error('rate') is-invalid @enderror" id="input1"
                                        placeholder="Rate (%)">
                                    @error('rate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                              
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pb-3 ">
                                    <label for="input4" class="form-label">Satus</label>
                                    <select name="active" id="active"
                                        class="form-control @error('active') is-invalid @enderror">
                                        <option value="">Select </option>
                                        @foreach ($activeOptions as $key => $active)
                                            <option value="{{ $key }}">
                                                {{ $active }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('active')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 pt-3">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    </div>
@endsection
