@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="position-relative">
                        <h5>Delivery Riders</h5>
                        <p class="mb-0">Manage your delivery riders</p>
                    </div>
                    <div class="ms-auto"><a href="{{ route('delivery-riders.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Rider</a></div>
                </div>
                <div class="table-responsive">
                    <table id="items-data-table" class="table">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($deliveryRiders->count())
                        <tbody>
                            @foreach ($deliveryRiders as $deliveryRider)
                            <tr>
                                <td>{{ $deliveryRider->name }}</td>
                                <td>{{$deliveryRider->phone}}</td>
                                <td>
                                    <x-status-text :status="$deliveryRider->status" trueLabel="Active" falseLabel="Inactive" />
                                </td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{ route('delivery-riders.edit', $deliveryRider->id) }}">
                                            <i class='bx bxs-edit'></i>
                                        </a>
                                        <a class="ms-3" href="{{ route('delivery-riders.show', $deliveryRider->id) }}">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        <a class="ms-3 delete-resource" href="javascript:void(0);" data-resource-id="{{$deliveryRider->id}}" data-resource-url="{{url('delivery-riders')}}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal"><i class="bx bxs-trash"></i></a>

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
            </div>
        </div>
    </div>
    @include('rocker-theme.layouts.partials.delete-modal')
</div>
<script>
    window.addEventListener('load', function() {

        var items_table = $('#items-data-table').DataTable({
            lengthChange: true,
        });
        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');
    });
</script>