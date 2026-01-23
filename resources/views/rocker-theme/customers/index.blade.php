@extends('rocker-theme.layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-lg-flex align-items-center mb-4 gap-3">
            <div class="position-relative">
                <input type="text" class="form-control ps-5 radius-30" id="search-customer" placeholder="Search Customer"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
            </div>
            <div class="ms-auto"><a href="{{ route('customers.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Customer</a></div>
        </div>
        <div class="table-responsive">
            <table id="customers-table" class="table">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @if ($customers->count())
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td>
                            {{ $customer->name() }}
                            <span class="text-secondary">{{$customer->email}}</span>
                        </td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{Str::limit( $customer->address, 20)}}</td>
                        <td>
                            <div class="d-flex align-items-center order-actions">
                                <a href="{{ route('customers.show', $customer->id) }}" class="me-3">
                                    <i class='bx bx-show'></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="me-3">
                                    <i class='bx bxs-edit'></i>
                                </a>
                                <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$customer->id}}" data-resource-url="{{url('customers')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
                @else
                <tbody>
                    <tr>
                        <td colspan="7">
                            <h6>No Result</h6>
                        </td>
                    </tr>
                </tbody>
                @endif
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $customers->links() }}
        </div>
    </div>
</div>

@include('rocker-theme.layouts.partials.delete-modal')

<script>
    let timer;
    window.addEventListener('load', function() {
        $('#search-customer').focus();
        $('#search-customer').on('input', function() {
            clearTimeout(timer);
            let search = $(this).val();

            if (search.length < 3) {
                $('#customers-table tbody').empty();
                return;
            }

            timer = setTimeout(() => {
                $.ajax({
                    url: "{{ route('search.customers') }}",
                    method: 'GET',
                    data: {
                        search
                    },
                    headers: {
                        'Accept': 'text/html'
                    },
                    success: function(html) {
                        $('#customers-table tbody').html(html);
                    }
                });
            }, 300);
        });

    });
</script>
@endsection