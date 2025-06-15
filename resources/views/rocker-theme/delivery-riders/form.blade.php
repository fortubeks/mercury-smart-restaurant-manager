@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="card-title">{{ isset($deliveryRider) ? 'Edit Delivery Rider' : 'Add New Delivery Rider' }}</h5>
                <hr>
                <div class="form-body mt-4">
                    <form class="row g-3" method="POST" action="{{ isset($deliveryRider) ? route('delivery-riders.update', $deliveryRider->id) : route('delivery-riders.store') }}" enctype="multipart/form-data">
                        @csrf
                        @if(isset($deliveryRider))
                        @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mt-3">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom01" class="form-label">Name</label>
                                        <input type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="validationCustom01" placeholder="Name" name="name"
                                            value="{{ old('name', isset($deliveryRider) ? $deliveryRider->name : '') }}">
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="note" class="form-label">Phone</label>
                                        <input type="text"
                                            class="form-control @error('phone') is-invalid @enderror" placeholder="Phone" name="phone"
                                            value="{{ old('phone', isset($deliveryRider) ? $deliveryRider->phone : '') }}">
                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="restaurant_id" class="form-label">Restaurant</label>
                                        <select id="restaurant_id" name="restaurant_id" class="form-select selectpicker">
                                            @foreach (restaurants() as $restaurant)
                                            <option value="{{ $restaurant->id }}"
                                                {{ old('restaurant_id', isset($deliveryRider) ? $deliveryRider->restaurant_id : '') == $restaurant->id ? 'selected' : '' }}>
                                                {{ $restaurant->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('restaurant_id')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        {{ isset($deliveryRider) ? 'Update Rider' : 'Create Rider' }}
                                    </button>
                                    <a href="{{ route('delivery-riders.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>