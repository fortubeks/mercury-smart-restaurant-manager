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
                        <h5>Suppliers</h5>
                    </div>
                    <div class="ms-auto"><a href="{{ route('suppliers.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add Supplier</a></div>
                </div>
                <div class="table-responsive">
                    <table id="suppliers-data-table" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Name</th>
                                <th>Phone</th>
                                <th>Total Supplies</th>
                                <th>Total Payment</th>
                                <th>Total Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($suppliers->count())
                        <tbody>

                            @foreach ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->contact_person_name }}</td>
                                <td>{{ $supplier->contact_person_phone }}</td>
                                <td>{{ formatCurrency( $supplier->getTotalSupplyAmount() ) }}</td>
                                <td>{{ formatCurrency($supplier->getTotalPaymentsAmount()) }}</td>
                                <td>{{ formatCurrency($supplier->getBalance()) }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a class="ms-3" href="{{route('suppliers.show',$supplier->id)}}"><i class="bx bxs-show"></i></a>
                                        <a class="ms-3" href="{{ route('suppliers.edit', $supplier->id) }}"><i class='bx bxs-edit'></i></a>
                                        <a class="ms-3" href="javascript:void(0);" data-resource-id="{{ $supplier->id }}" data-resource-url="{{ url('suppliers') }}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
                                            <i class="bx bxs-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tbody>
                            <tr>
                                <td colspan="8">
                                    <h6>No Available Supplier</h6>
                                </td>
                            </tr>
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        var suppliers_table = $('#suppliers-data-table').DataTable({
            lengthChange: false,
        });

        suppliers_table.buttons().container().appendTo('#suppliers-data-table_wrapper .col-md-6:eq(0)');
    });
</script>