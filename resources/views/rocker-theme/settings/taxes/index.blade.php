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
                        <h4>Taxes</h4>
                    </div>
                    <div class="ms-auto"><a href="{{ route('taxes.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add New Tax</a></div>
                </div>
                <div class="table-responsive">
                    <table id="example2" class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Rate</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($taxes as $tax)
                            <tr>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->rate }}</td>
                                <td>{{ $tax->is_active }}</td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{ route('taxes.edit', $tax->id) }}">
                                            <i class='bx bxs-edit'></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <td colspan="5">
                                <p>No Taxes</p>
                            </td>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>