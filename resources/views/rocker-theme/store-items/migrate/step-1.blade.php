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
                        <li class="breadcrumb-item active" aria-current="page">Migrate From One Outlet to Another</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <!--include flash message manually if you wish -->
                        <form action="{{ url('store/migrate-items') }}" method="get">
                            <div class="row mx-auto">
                                <!-- Category select -->
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <select id="item-category" class="form-select form-control" name="outlet_a">
                                        <option value="">Select Outlet A</option>
                                        @foreach ($outlets as $outlet)
                                            <option value="{{ $outlet->id }}">
                                                {{ $outlet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Item SubCategory select -->
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                                    <select id="item-subcategory" class="form-select form-control" name="outlet_b">
                                        <option value="">Select Outlet B</option>
                                        @foreach ($outlets as $outlet)
                                            <option value="{{ $outlet->id }}">
                                                {{ $outlet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Submit button -->
                                <div class="col-3">
                                    <input type="hidden" name="type" value="drinks">
                                    <button class="btn btn-primary" type="submit">Proceed</button>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
        <hr>
        
    </div>
    <!--end row-->
    </div>

@endsection
