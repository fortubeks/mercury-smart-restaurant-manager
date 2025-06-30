@extends('rocker-theme.layouts.app')
@section('content')
<h5 class="mb-3">Migrate Items</h5>

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="card">
            <div class="card-body p-4">
                <!--include flash message manually if you wish -->
                <form id="filterForm" action="{{ route('store-item.migrate-items') }}" method="get">
                    <div class="row mx-auto">
                        <!-- Category select -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                            <select id="item-category" class="form-select form-control" name="type" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Select Type</option>
                                <option value="store" {{ request('type') == 'store' ? 'selected' : '' }}>From Store to Store</option>
                                <option value="outlet" {{ request('type') == 'outlet' ? 'selected' : '' }}>From Outlet to Outlet</option>
                            </select>
                        </div>
                        @if(request('type') == 'store')
                        <!-- Category select -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                            <select id="item-category" class="form-select form-control" name="outlet_a">
                                <option value="">Select Store A</option>
                                @foreach ($stores as $store)
                                <option value="{{ $store->id }}">
                                    {{ $store->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Item SubCategory select -->
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 pb-3">
                            <select id="item-subcategory" class="form-select form-control" name="outlet_b">
                                <option value="">Select Store B</option>
                                @foreach ($stores as $store)
                                <option value="{{ $store->id }}">
                                    {{ $store->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @else
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
                        @endif

                        <!-- Submit button -->
                        <div class="col-3">
                            <button class="btn btn-primary" type="submit">Proceed</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<hr>

@endsection