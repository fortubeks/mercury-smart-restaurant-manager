@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class=" col-4 mb-3"></div>
                            <div class=" col-4 mb-3">
                                <label class="form-label text-center">Date</label>
                                <input type="date" class="form-control datepicker flatpickr-input active"
                                    name="date" data-toggle="flatpickr" value="{{ $dailySale->shift_date }}"
                                    data-max-date="{{$lastAuditedDate}}" id="shift" data-min-date="{{$firstAuditedDate}}">
                            </div>
                            @if(restaurants()->count() > 1)
                            <div class=" col-4 mt-4">
                                <a href="{{route('consolidated-report')}}" class="btn btn-dark">View Consolidated Report</a>
                            </div>
                            @else
                            <div class="col-4 mb-3"></div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table id="summary-data-table" class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th class="money text-end">Cash</th>
                                        <th class="money text-end">POS</th>
                                        <th class="money text-end">Transfer</th>
                                        <th class="money text-end">Wallet</th>
                                        <th class="money text-end">Credit</th>
                                        <th class="money text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outletSales as $outletSale)
                                    <tr>
                                        <td><strong>{{ $outletSale->outlet->name }}</strong></td>
                                        <td class="money text-end">{{ $outletSale->cash }}</td>
                                        <td class="money text-end">{{ $outletSale->pos }}</td>
                                        <td class="money text-end">{{ $outletSale->transfer }}</td>
                                        <td class="money text-end">{{ $outletSale->wallet }}</td>
                                        <td class="money text-end">{{ $outletSale->credit }}</td>
                                        <td class="money text-end">{{ $outletSale->total }}</td>
                                    </tr>
                                    @endforeach


                                    <tr>
                                        <td><strong>Total Sales</strong></td>
                                        <td class="money text-end">{{ $dailySale->cash }}</td>
                                        <td class="money text-end">{{ $dailySale->pos }}</td>
                                        <td class="money text-end">{{ $dailySale->transfer }}</td>
                                        <td class="money text-end">{{ $dailySale->wallet }}</td>
                                        <td class="money text-end">{{ $dailySale->credit }}</td>
                                        <td class="money text-end"><strong>{{ $dailySale->total }}</strong></td>
                                    </tr>

                                    <!-- <tr>
                                    <td><strong>Settlements</strong></td>
                                    <td class="money text-end">{{ $dailySale->settlement_cash }}</td>
                                    <td class="money text-end">{{ $dailySale->settlement_pos }}</td>
                                    <td class="money text-end">{{ $dailySale->settlement_transfer }}</td>
                                    <td class="money text-end">{{ $dailySale->settlement_wallet }}</td>
                                    <td class="money text-end">0</td> 
                                    <td class="money text-end">{{ $dailySale->settlement_total }}</td>
                                </tr> -->
                                    <!-- <tr>
                                    <td><strong>Grand Total</strong></td>
                                    <td class="money text-end">{{ $dailySale->cash }}</td>
                                    <td class="money text-end">{{ $dailySale->pos }}</td>
                                    <td class="money text-end">{{ $dailySale->transfer }}</td>
                                    <td class="money text-end">{{ $dailySale->wallet }}</td>
                                    <td class="money text-end">{{ $dailySale->credit }}</td>
                                    <td class="money text-end"><strong>{{ $dailySale->total }}</strong></td>
                                </tr> -->
                                </tbody>
                            </table>
                        </div>
                        <div class="row mb-3 mt-3">
                            <hr>
                            <div class="col-12 d-flex align-items-center fw-bold">
                                <h6 class="me-3">Grand Total:</h6>
                                <p class="mb-1">
                                    <span>Total Sales:</span>
                                    <span class="ms-1 text-primary">{{ number_format($dailySale->total, 2) }}</span>

                                    <span class="mx-2">+</span>

                                    <span>Total Settlement:</span>
                                    <span class="ms-1 text-success">{{ number_format(0, 2) }}</span>

                                    <span class="mx-2">+</span>

                                    <span>Total Deposits:</span>
                                    <span class="ms-1 text-warning">{{ number_format(0, 2) }}</span>

                                    <span class="mx-2">=</span>

                                    <span class="text-dark border-bottom border-2">{{ number_format($dailySale->total, 2) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row mb-3 mt-3">
                            <hr>
                            <div class="col-12 d-flex align-items-center fw-bold">
                                <h6 class="me-3">Expected Closing Cash Balance:</h6>
                                <p class="mb-1">
                                    <span>Total Cash Received:</span>
                                    <span class="ms-1 text-primary">{{ number_format($dailySale->total, 2) }}</span>

                                    <span class="mx-2">+</span>

                                    <span>Previous Day Cash Balance:</span>
                                    <span class="ms-1 text-success">{{ number_format($previousDayCashBalance, 2) }}</span>

                                    <span class="mx-2">-</span>

                                    <span>Cash Outflow:</span>
                                    <span class="ms-1 text-danger">{{ number_format($dailySale->cash_outflow, 2) }}</span>

                                    <span class="mx-2">=</span>

                                    <span class="text-dark border-bottom border-2">{{ number_format($dailySale->expected_cash_balance, 2) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3 mt-3">
                            <hr>
                            <div class="col-12 d-flex align-items-center fw-bold">
                                <h6 class="me-3">Actual Closing Cash Balance:</h6>
                                <p class="mb-1">
                                    <span class="text-dark border-bottom border-2">{{ number_format($dailySale->expected_cash_balance, 2) }}</span>
                                </p>
                            </div>
                        </div>


                        <div class="d-flex justify-content-end">
                            <!-- <a href="{{route('daily-sales.edit',$dailySale->id)}}" class="btn btn-primary btn-sm mx-3" type="button" class="ms-3"><i class="bx bxs-save"></i>Update</a> -->
                            @if($is_latest)
                            <button class="btn btn-danger btn-sm" type="button" class="ms-3 delete-sale" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bx bxs-trash"></i>Delete</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('rocker-theme.daily-sales.partials.create-summary')
    </div>

    <!--end row-->
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this sales audit?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{url('daily-sales/'.$dailySale->id)}}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

        var restaurant_table = $('#restaurant-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });

        var credit_table = $('#credit-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });

        var summary_table = $('#summary-data-table').DataTable({
            lengthChange: false,
            searching: false,
            ordering: false,
            paging: false,
            autoWidth: false,
            buttons: [{
                    extend: 'excel',
                    title: 'Sales Summary',
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c[r=A1] t', sheet).attr('style', 'font-size:10px;');
                    }
                },
                {
                    extend: 'pdf',
                    title: 'Sales Summary',
                    customize: function(doc) {
                        doc.content[1].text = {
                            text: 'Sales Summary',
                            fontSize: 10
                        };
                    }
                },
                {
                    extend: 'print',
                    title: 'Sales Summary',
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend('<p style="text-align: left;">Date:{{$dailySale->shift_date}}</p>')
                            .find('h1') // Target the title "Sales Summary"
                            .css({
                                'font-size': '12pt',
                                'font-weight': 'bold'
                            }); // Add title to the print view
                    }
                }
            ],
        });

        summary_table.buttons().container().appendTo('#summary-data-table_wrapper .col-md-6:eq(0)');
        restaurant_table.buttons().container().appendTo('#restaurant-data-table_wrapper .col-md-6:eq(0)');
        credit_table.buttons().container().appendTo('#credit-data-table_wrapper .col-md-6:eq(0)');


        $('#shift').change(function() {
            // Get the selected date value
            var selectedDate = $(this).val();

            var url = "{{ url('view-audit') }}" + "/" + selectedDate;
            window.location.href = url;
        });

        document.querySelectorAll('.number-format').forEach(input => {
            const value = parseFloat(input.value.replace(/,/g, '')); // Remove any existing commas
            if (!isNaN(value)) {
                input.value = value.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
        });

    });
</script>