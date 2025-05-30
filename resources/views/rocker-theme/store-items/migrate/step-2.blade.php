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
                    <li class="breadcrumb-item active" aria-current="page">Issue out drinks</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row mb-5">
        <div class="col-xl-12 mx-auto">
            <form action="{{ url('store/migrate-items') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="hidden" name="outletA" value="{{$outletA->id}}">
                    <input type="hidden" name="outletB" value="{{$outletB->id}}">
                </div>
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>{{$outletA->name}}</th>
                            <th>Quantity to Send</th>
                            <th>{{$outletB->name}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($outletAItems as $outletAItem)
                        @php
                        $storeItemId = $outletAItem->store_item_id;
                        $outletBItem = $outletBItems->get($storeItemId);
                        @endphp
                        <tr>
                            <td>{{ $outletAItem->storeItem->code }}</td>
                            <td>{{ $outletAItem->storeItem->name.'('.$outletAItem->qty.')' }}</td>
                            <td>
                                <input type="number" class="form-control" name="quantities[{{ $outletAItem->id }}]" min="1" max="{{ $outletAItem->qty }}">
                            </td>
                            <td>{{ $outletBItem ? $outletBItem->storeItem->name.'('.$outletBItem->qty.')' : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                        <label for="recipient">Notes:</label><br>
                        <input type="text" id="recipient" class="form-control" name="note">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary mt-3">Give Out</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end row-->
@endsection