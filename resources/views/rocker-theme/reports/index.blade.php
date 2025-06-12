@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->
        <h4>Sales</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded" data-url="{{ route('reports.daily-sales-summary') }}">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Daily Sales & Audit Report</span>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary">
                        <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2">
                        <span>Report By Staff</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div><!--end row-->
        <hr>
        <h4>Purchases</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Purchases Report</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Purchases By Category</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div><!--end row-->
        <hr>
        <h4>Profit & Loss</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded" data-bs-toggle="modal" data-bs-target="#generalReportModal">
                    <div class=" font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Profit & Loss Report</span>
                    </div>
                </div>
            </div>
        </div><!--end row-->
        <hr>
        <h4>Tax</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i></div>
                    <div class="ms-2"> <span>Tax Report</span></div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div><!--end row-->
        <hr>
        <h4>Expenses</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Expense Report</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Expense By Category</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div><!--end row-->
        <hr>
        <h4>Room Performance</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Room Report</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>Room Category Report</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div><!--end row-->
        <hr>
        <h4>KPI Reports</h4>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 row-cols-xl-5 g-3">
            <div class="col">
                <div class="d-flex align-items-center theme-icons shadow-sm p-2 cursor-pointer rounded position-relative">
                    <div class="font-22 text-primary"> <i class="lni lni-book"></i>
                    </div>
                    <div class="ms-2"> <span>KPI Report</span>
                    </div>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-pill" style="font-size: 10px;">
                        Coming Soon
                    </span>
                </div>
            </div>
        </div><!--end row-->

    </div>
    <div class="modal fade" id="generalReportModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Download Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('reports.download') }}" method="GET">
                                <div class="row row-cols-1 g-3 row-cols-lg-auto align-items-center">
                                    <div class="col">
                                        <select class="form-select" id="restaurant_id" name="restaurant_id" required>
                                            <option value="">--Select Restaurant--</option>
                                            @foreach(restaurants() as $restaurant)
                                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <input class="form-control date-format" type="date" id="search_start" name="start_date" required>
                                    </div>
                                    <div class="col">
                                        <input class="form-control date-format" type="date" id="search_end" name="end_date" required>
                                    </div>
                                    <div class="col">
                                        <select class="form-select" name="report_type" required>
                                            <option value="">--Select Report--</option>
                                            <option value="profit_loss">Profit & Loss</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary px-4">Download Report</button>
                                    </div>
                                </div><!--end row-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        $('.theme-icons').on('click', function() {
            var url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
        });
    });
</script>