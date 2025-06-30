@extends('rocker-theme.layouts.app')
@section('content')

<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title">{{ isset($store) ? 'Edit Store' : 'Add New Store' }}</h5>
        <hr>
        <div class="form-body mt-4">
            <form class="row g-3" method="POST" action="{{ isset($store) ? route('stores.update', $store->id) : route('stores.store') }}">
                @csrf
                @if(isset($store))
                @method('PUT')
                @endif
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 pb-3">
                        <label for="input1" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $store->name ?? '') }}" required>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 d-flex justify-content-end pt-3">
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection