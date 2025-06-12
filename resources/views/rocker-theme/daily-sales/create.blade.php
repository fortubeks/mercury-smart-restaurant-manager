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
                    <li class="breadcrumb-item active" aria-current="page">Create an Audit</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="ms-auto">
                <a href="" class="btn btn-dark">View Sales</a>
            </div> --}}
    </div>
    <!--end breadcrumb-->

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <!--include flash message manually if you wish -->
                    <form action="{{ route('daily-sales.store') }}" id="sales-form" method="post">
                        @csrf
                        <div class="row mb-5 d-flex justify-content-center">
                            <div class=" col-4 mb-3">
                                <label class="form-label text-center">Date</label>
                                <input type="date" id="datepicker" class="form-control @error('date') is-invalid @enderror datepicker flatpickr-input active"
                                    name="shift_date" data-toggle="flatpickr"
                                    class="form-control date flatpickr-input active" readonly="readonly"
                                    @isset($current_audit_date)
                                    data-mindate="{{ $current_audit_date }}"
                                    @endisset>
                                @include('alerts.error-feedback', [
                                'field' => 'date',
                                ])
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col">

                            </div>
                            <div class="col">
                                <h6>Cash</h6>
                            </div>
                            <div class="col">
                                <h6>POS</h6>
                            </div>
                            <div class="col">
                                <h6>Transfer</h6>
                            </div>
                            <div class="col">
                                <h6>Wallet</h6>
                            </div>
                            <div class="col">
                                <h6>Credit</h6>
                            </div>
                            <div class="col">
                                <h6>Total</h6>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col ">
                                <h6>Accommodation</h6>
                            </div>
                            <div class="col ">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('accomodation_cash') is-invalid @enderror"
                                        name="accomodation_cash" id="accomodation_cash" inputmode="decimal"
                                        min="0" value="{{ $sales['accomodation']['cash'] }}" step="any"
                                        placeholder="Cash">
                                    @error('accomodation_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col ">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('accomodation_pos') is-invalid @enderror"
                                        name="accomodation_pos" inputmode="decimal" min="0"
                                        value="{{ $sales['accomodation']['pos'] }}" step="any" placeholder="POS">
                                    @error('accomodation_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col ">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('accomodation_transfer') is-invalid @enderror"
                                        name="accomodation_transfer" inputmode="decimal" min="0"
                                        value="{{ $sales['accomodation']['transfer'] }}" step="any"
                                        placeholder="Transfer">
                                    @error('accomodation_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col ">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('accomodation_wallet') is-invalid @enderror"
                                        name="accomodation_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['accomodation']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('accomodation_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col ">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('accomodation_credit') is-invalid @enderror"
                                        name="accomodation_credit" inputmode="decimal" min="0"
                                        value="{{ $sales['accomodation']['credit'] }}" step="any" placeholder="Credit">
                                    @error('accomodation_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col ">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('accomodation_total') is-invalid @enderror"
                                        name="accomodation_total" id="accomodation-total"
                                        value="{{ $sales['accomodation']['total'] }}" readonly placeholder="Total">
                                    @error('accomodation_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <h6>Bar</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control @error('bar_cash') is-invalid @enderror"
                                        name="bar_cash" value="{{ $sales['bar']['cash'] }}" placeholder="Cash">
                                    @error('bar_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control @error('bar_pos') is-invalid @enderror"
                                        name="bar_pos" value="{{ $sales['bar']['pos'] }}" placeholder="POS">
                                    @error('bar_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('bar_transfer') is-invalid @enderror"
                                        name="bar_transfer" value="{{ $sales['bar']['transfer'] }}"
                                        placeholder="Transfer">
                                    @error('bar_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('bar_wallet') is-invalid @enderror"
                                        name="bar_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['bar']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('bar_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('bar_credit') is-invalid @enderror"
                                        name="bar_credit" value="{{ $sales['bar']['credit'] }}" placeholder="Credit">
                                    @error('bar_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('bar_total') is-invalid @enderror" name="bar_total"
                                        value="{{ $sales['bar']['total'] }}" readonly placeholder="Total">
                                    @error('bar_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <h6>Restaurant</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('restaurant_cash') is-invalid @enderror"
                                        name="restaurant_cash" value="{{ $sales['restaurant']['cash'] }}"
                                        placeholder="Cash">
                                    @error('restaurant_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('restaurant_pos') is-invalid @enderror"
                                        name="restaurant_pos" value="{{ $sales['restaurant']['pos'] }}" placeholder="POS">
                                    @error('restaurant_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('restaurant_transfer') is-invalid @enderror"
                                        value="{{ $sales['restaurant']['transfer'] }}" name="restaurant_transfer"
                                        placeholder="Transfer">
                                    @error('restaurant_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('restaurant_wallet') is-invalid @enderror"
                                        name="restaurant_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['restaurant']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('restaurant_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('restaurant_credit') is-invalid @enderror"
                                        name="restaurant_credit" value="{{ $sales['restaurant']['credit'] }}"
                                        placeholder="Credit">
                                    @error('restaurant_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('restaurant_total') is-invalid @enderror"
                                        name="restaurant_total" value="{{ $sales['restaurant']['total'] }}" readonly
                                        placeholder="Total">
                                    @error('restaurant_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @activeModule('laundry_active')
                        <div class="row mb-2">
                            <div class="col">
                                <h6>Laundry</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('laundry_cash') is-invalid @enderror"
                                        name="laundry_cash" value="{{ $sales['laundry']['cash'] }}"
                                        placeholder="Cash">
                                    @error('laundry_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('laundry_pos') is-invalid @enderror"
                                        name="laundry_pos" value="{{ $sales['laundry']['pos'] }}" placeholder="POS">
                                    @error('laundry_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('laundry_transfer') is-invalid @enderror"
                                        value="{{ $sales['laundry']['transfer'] }}" name="laundry_transfer"
                                        placeholder="Transfer">
                                    @error('laundry_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('laundry_wallet') is-invalid @enderror"
                                        name="laundry_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['laundry']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('laundry_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('laundry_credit') is-invalid @enderror"
                                        name="laundry_credit" value="{{ $sales['laundry']['credit'] }}"
                                        placeholder="Credit">
                                    @error('laundry_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('laundry_total') is-invalid @enderror"
                                        name="laundry_total" value="{{ $sales['laundry']['total'] }}" readonly
                                        placeholder="Total">
                                    @error('laundry_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endactiveModule
                        @activeModule('venue_active')
                        <div class="row mb-2">
                            <div class="col">
                                <h6>Venue</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('venue_cash') is-invalid @enderror"
                                        name="venue_cash" value="{{ $sales['venue']['cash'] }}"
                                        placeholder="Cash">
                                    @error('venue_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('venue_pos') is-invalid @enderror"
                                        name="venue_pos" value="{{ $sales['venue']['pos'] }}" placeholder="POS">
                                    @error('venue_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('venue_transfer') is-invalid @enderror"
                                        value="{{ $sales['venue']['transfer'] }}" name="venue_transfer"
                                        placeholder="Transfer">
                                    @error('venue_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('venue_wallet') is-invalid @enderror"
                                        name="venue_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['venue']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('venue_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('venue_credit') is-invalid @enderror"
                                        name="venue_credit" value="{{ $sales['venue']['credit'] }}"
                                        placeholder="Credit">
                                    @error('venue_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('venue_total') is-invalid @enderror"
                                        name="venue_total" value="{{ $sales['venue']['total'] }}" readonly
                                        placeholder="Total">
                                    @error('venue_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endactiveModule
                        @activeModule('gym_active')
                        <div class="row mb-2">
                            <div class="col">
                                <h6>Gym</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('gym_cash') is-invalid @enderror"
                                        name="gym_cash" value="{{ $sales['gym']['cash'] }}"
                                        placeholder="Cash">
                                    @error('gym_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('gym_pos') is-invalid @enderror"
                                        name="gym_pos" value="{{ $sales['gym']['pos'] }}" placeholder="POS">
                                    @error('gym_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('gym_transfer') is-invalid @enderror"
                                        value="{{ $sales['gym']['transfer'] }}" name="gym_transfer"
                                        placeholder="Transfer">
                                    @error('gym_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('gym_wallet') is-invalid @enderror"
                                        name="gym_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['gym']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('gym_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('gym_credit') is-invalid @enderror"
                                        name="gym_credit" value="{{ $sales['gym']['credit'] }}"
                                        placeholder="Credit">
                                    @error('gym_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('gym_total') is-invalid @enderror"
                                        name="gym_total" value="{{ $sales['gym']['total'] }}" readonly
                                        placeholder="Total">
                                    @error('gym_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endactiveModule
                        @activeModule('swimming_active')
                        <div class="row mb-2">
                            <div class="col">
                                <h6>Swimming</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('swimming_cash') is-invalid @enderror"
                                        name="swimming_cash" value="{{ $sales['swimming']['cash'] }}"
                                        placeholder="Cash">
                                    @error('swimming_cash')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('swimming_pos') is-invalid @enderror"
                                        name="swimming_pos" value="{{ $sales['swimming']['pos'] }}" placeholder="POS">
                                    @error('swimming_pos')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('swimming_transfer') is-invalid @enderror"
                                        value="{{ $sales['swimming']['transfer'] }}" name="swimming_transfer"
                                        placeholder="Transfer">
                                    @error('swimming_transfer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('swimming_wallet') is-invalid @enderror"
                                        name="swimming_wallet" inputmode="decimal" min="0"
                                        value="{{ $sales['swimming']['wallet'] }}" step="any" placeholder="Wallet">
                                    @error('swimming_wallet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('swimming_credit') is-invalid @enderror"
                                        name="swimming_credit" value="{{ $sales['swimming']['credit'] }}"
                                        placeholder="Credit">
                                    @error('swimming_credit')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control @error('swimming_total') is-invalid @enderror"
                                        name="swimming_total" value="{{ $sales['swimming']['total'] }}" readonly
                                        placeholder="Total">
                                    @error('swimming_total')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @endactiveModule

                        @foreach($extra_outlets as $key => $outlet)
                        <div class="row mb-2">
                            <div class="col">
                                <h6>{{$outlet->name}}</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="outlet_cash[]" value="{{ $extra_outlets_sales[$key]['cash'] }}" placeholder="Cash">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="outlet_pos[]" value="{{ $extra_outlets_sales[$key]['pos'] }}" placeholder="POS">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="outlet_transfer[]" value="{{ $extra_outlets_sales[$key]['transfer'] }}" placeholder="Transfer">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="outlet_wallet[]" value="{{ $extra_outlets_sales[$key]['wallet'] }}" placeholder="Wallet">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="outlet_credit[]" value="{{ $extra_outlets_sales[$key]['credit'] }}" placeholder="Credit">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="hidden" name="outlet_id[]" value="{{$outlet->id}}">
                                    <input type="number" class="form-control" name="outlet_total[]" value="{{ $extra_outlets_sales[$key]['total'] }}" placeholder="Total">
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="row mb-3 mt-3">
                            <div class="col">
                                <hr>
                                <h5>Total</h5>
                            </div>
                            <div class="col">
                                <hr>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="{{ $total_cash }}" placeholder="Cash">
                                </div>
                            </div>
                            <div class="col">
                                <hr>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="{{ $total_pos }}" placeholder="POS">
                                </div>
                            </div>
                            <div class="col">
                                <hr>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="{{ $total_transfer }}" placeholder="Transfer">
                                </div>
                            </div>
                            <div class="col">
                                <hr>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="{{ $total_wallet }}" placeholder="Wallet">
                                </div>
                            </div>
                            <div class="col">
                                <hr>
                                <div class="form-group">
                                    <input type="number" class="form-control" value="{{ $total_credit }}" placeholder="Credit">
                                </div>
                            </div>
                            <div class="col">
                                <hr>
                                <input class="form-control" id="final-total" value="{{ $sales['grand_total'] }}"
                                    name="final_total" type="number" readonly placeholder="Final Total">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col">
                                <h6>Settlements</h6>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="settlement_cash" value="{{ $settlementSales['cash'] }}" placeholder="Cash">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="settlement_pos" value="{{ $settlementSales['pos'] }}" placeholder="POS">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="settlement_transfer" value="{{ $settlementSales['transfer'] }}" placeholder="Transfer">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="settlement_wallet" value="{{ $settlementSales['wallet'] }}" placeholder="Wallet">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="settlement_credit" value="{{ $settlementSales['credit'] }}" placeholder="Credit">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="settlement_total" value="{{ $settlementSales['total'] }}" placeholder="Total">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <hr>
                            <div class="col-12 d-flex align-items-center fw-bold">
                                <h6 class="me-3">Grand Total:</h6>

                                <span>Total Sales:</span>
                                <span class="ms-1 text-primary">{{ number_format($sales['grand_total'], 2) }}</span>

                                <span class="mx-2">+</span>

                                <span>Total Settlement:</span>
                                <span class="ms-1 text-success">{{ number_format($settlementSales['total'], 2) }}</span>

                                <span class="mx-2">+</span>

                                <span>Total Deposits:</span>
                                <span class="ms-1 text-warning">{{ number_format($totalWalletDeposits, 2) }}</span>

                                <span class="mx-2">+</span>

                                <span>Previous Cash at Hand:</span>
                                <span class="ms-1 text-danger">{{ number_format($previousDayCashAtHand, 2) }}</span>

                                <span class="mx-2">=</span>

                                <span class="text-dark border-bottom border-2">{{ number_format($grand_total_sales, 2) }}</span>
                                <input value="{{ $grand_total_sales }}" name="grand_total" type="hidden">
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <hr>
                            <div class="col-12 d-flex align-items-center fw-bold">
                                <input value="{{ $totalWalletDeposits }}" name="total_deposits" type="hidden">
                                <input value="{{ $totalCashOutflows }}" name="total_outflows" type="hidden">
                                <input class="form-control" value="{{ $cashAccountBalance }}" name="cash_account_balance" type="hidden">
                                <span>Total Cash Outflows:</span>
                                <span class="ms-1 text-danger">{{ number_format($totalCashOutflows, 2) }}</span>

                                <span class="mx-2">,</span>

                                <span>Current Cash Balance:</span>
                                <span class="ms-1 text-success">{{ number_format($cashAccountBalance, 2) }}</span>


                            </div>
                        </div>


                        <div class="row mb-3 mt-3">

                            <!-- <div class="col-3">
                                <textarea class="form-control" name="notes" placeholder="Add note if any" id="" cols="5"
                                    rows="2">{{ old('notes') }}</textarea>
                            </div> -->
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary btn-sm" type="button" class="ms-3 delete-sale" data-bs-toggle="modal" data-bs-target="#submitModal"><i class="bx bxs-save"></i>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.daily-sales.partials.create-summary')

</div>


<div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Sales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to sign off on this sales audit?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success btn-submit">Yes, Confirmed</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        document.getElementById('loading-screen').style.display = 'none';
        var accomodation_table = $('#accomodation-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });
        var restaurant_table = $('#restaurant-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });
        var bar_table = $('#bar-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });
        var credit_table = $('#credit-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });

        accomodation_table.buttons().container().appendTo('#accomodation-data-table_wrapper .col-md-6:eq(0)');
        restaurant_table.buttons().container().appendTo('#restaurant-data-table_wrapper .col-md-6:eq(0)');
        bar_table.buttons().container().appendTo('#bar-data-table_wrapper .col-md-6:eq(0)');
        credit_table.buttons().container().appendTo('#credit-data-table_wrapper .col-md-6:eq(0)');

        // Get the minDate from the dataset
        var minDate = $("#datepicker").data("mindate");

        // Calculate the maxDate by adding 1 days to the minDate
        var maxDate = new Date(minDate);
        var currentDate = new Date(minDate);
        maxDate.setDate(maxDate.getDate() + 1);
        // Initialize flatpickr
        flatpickr("#datepicker", {
            minDate: minDate,
            maxDate: currentDate,
            dateFormat: "Y-m-d",
            defaultDate: currentDate, // Set the default date
            allowInput: false, // Disable manual input
        });
        $('.btn-submit').click(function() {
            $('#sales-form').submit();
        });

        $('input[type="number"]').prop('readonly', true);
    });
</script>
@endsection