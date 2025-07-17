@extends('rocker-theme.layouts.app')
@section('content')
<div class="card">
    <div class="card-body p-4">
        <h5 class="card-title">{{ isset($customer) ? 'Edit Customer' : 'Add New Customer' }}</h5>
        <hr>
        <div class="form-body mt-4">
            <form class="row g-3" method="POST" action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}">
                @csrf
                @if(isset($customer))
                @method('PUT')
                @endif
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $customer->first_name ?? '') }}" placeholder="Enter first name">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}" placeholder="Enter last name (optional)">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $customer->email ?? '') }}" placeholder="Enter email (optional)">
                    </div>
                    <!--Gender -->
                    <div class="col-md-3 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">--Select Gender--</option>
                            @foreach(getModelList('genders') as $gender => $value)
                            <option value="{{$value}}" {{ (old('gender', $asset->gender ?? '') == $value) ? 'selected' : '' }}>{{$gender}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="phone_code" class="form-label">Phone Code</label>
                        <select class="form-select" id="phone_code" name="phone_code">
                            <option value="+234">🇳🇬 +234</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="phone" class="form-label">Primary Phone</label>
                        <input type="number" min="7" class="form-control" id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" placeholder="e.g. 8012345678">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="other_phone" class="form-label">Other Phone</label>
                        <input type="number" min="7" class="form-control" id="other_phone" name="other_phone" value="{{ old('other_phone', $customer->other_phone ?? '') }}" placeholder="Optional">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="birthday" class="form-label">Birthday</label>
                        <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday', $customer->birthday ?? '') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $customer->address ?? '') }}" placeholder="Optional">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" name="country">
                            <option value="">Select Country</option>
                            @foreach (getModelList('countries') as $country)
                            <option value="{{ $country->name }}" {{ $country->id == 161 ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="state_id" class="form-label">State / City</label>
                        <select class="form-select" id="state_id" name="state_id">
                            <option value="">Select State / City</option>
                            @foreach (getModelList('states') as $state)
                            <option value="{{ $state->id }}" {{ old('state_id', $customer->state_id ?? '') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div><!--end row-->
    </div>
</div>

@include('rocker-theme.layouts.partials.delete-modal')

<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection