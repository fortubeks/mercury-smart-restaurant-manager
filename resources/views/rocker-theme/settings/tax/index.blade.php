@extends('dashboard.layouts.app')

<style>
    .tax-photo {
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
                        <li class="breadcrumb-item active" aria-current="page">taxs</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('settings.taxs.create') }}" class="btn btn-dark">Add New</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <!--include flash message manually if you wish -->
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                               
                                <th>Name</th>
                                <th>Rate</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($taxs->count())
                            <tbody>

                                @foreach ($taxs as $tax)
                                @php
                                $taxStatus = $tax->active;
                                $taxStatusColor = '';
                                if ($taxStatus == '1') {
                                    $taxStatusColor = 'text-success';
                                }
                                if ($taxStatus == '0') {
                                    $taxStatusColor = 'text-danger';
                                }
                            @endphp
                                    <tr>
                                        <td>{{ $tax->name }}</td>
                                        <td>{{ $tax->rate }}</td>
                                        {{-- {{ dd($tax->active) }} --}}
                                        <td class="{{$taxStatusColor}}">{{ $tax->active }}</td>
                                        <td>{{ $tax->created_at->diffForhumans() }}</td>
                                        <td>

                                            <div class="d-flex order-actions">
                                                <a href="{{ route('settings.taxs.edit', $tax->id) }}">
                                                    <i class='bx bxs-edit'></i>
                                                </a>
                                                <a href="#" onclick="confirmDelete(event);" class="ms-3">
                                                    <i class='bx bxs-trash'></i>
                                                </a>
                                            </div>
                                            <form id="delete-form" action="{{ route('settings.taxs.destroy', $tax->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <script>
                                                function confirmDelete(event) {
                                                    event.preventDefault();
                                                    if (confirm('Are you sure you want to delete this tax?')) {
                                                        document.getElementById('delete-form').submit();
                                                    }
                                                }
                                            </script>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                        <td colspan="5">
                            <h4>No Available Tax(s)</h4>
                        </td>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    </div>
@endsection
