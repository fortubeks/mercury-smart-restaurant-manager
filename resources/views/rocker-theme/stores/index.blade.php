@extends('rocker-theme.layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <h4>Stores</h4>
            </div>
            <div class="ms-auto">
                <a href="{{ route('stores.create') }}" class="btn btn-sm btn-dark"><i class="bx bx-plus mr-2"></i>New Store</a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="accounts-table" class="table">
                <thead>
                    <tr>
                        <th>Name</th>

                        <th>Action</th>
                    </tr>
                </thead>
                @if ($stores->count())
                <tbody>
                    @foreach ($stores as $store)
                    <tr>
                        <td>{{ $store->name }}</td>
                        <td>
                            <div class="d-flex align-items-center order-actions">
                                <a href="{{ url('store-items?store_id='. $store->id) }}" title="Store items"><i class='bx bx-spreadsheet'></i></a>
                                <a href="{{ route('stores.edit', $store->id) }}" title="Edit" class="ms-3"><i class='bx bx-pencil'></i></a>
                                <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$store->id}}" data-resource-url="{{url('stores')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                <tbody>
                    <tr>
                        <td colspan="7">
                            <h5>No store. Create one now.</h5>
                        </td>
                    </tr>
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>

@include('rocker-theme.layouts.partials.delete-modal')


<script>
    window.addEventListener('load', function() {

    });
</script>
@endsection