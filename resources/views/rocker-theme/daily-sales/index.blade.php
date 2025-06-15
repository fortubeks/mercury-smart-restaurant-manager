@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->


        <div class="card">
            <div class="card-body">
                <!--include flash message manually if you wish -->
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="position-relative">
                        <h4>Daily Sales</h4>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('daily-sales.create') }}" class="btn btn-sm btn-dark"><i class="bx bx-plus mr-2"></i>New Audit</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="sales-data-table" class="table mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th> Cash</th>
                                <th> POS</th>
                                <th> Transfer</th>
                                <th> Wallet</th>
                                <th> Credit</th>
                                <th data-toggle="tooltip" title="Total Sales for the day">Total Sales</th>
                                <th data-toggle="tooltip" title="Deposits & Settlements">Deposits</th>
                                <th data-toggle="tooltip" title="Expenses & Purchases">Cash Expenses</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                            <tr>
                                <td><a class="loading-screen" href="{{route('daily-sales.show', $sale->id)}}">{{ $sale->shift_date }}</a></td>
                                <td>₦{{number_format( $sale->cash) }}</td>
                                <td>₦{{ number_format($sale->pos) }}</td>
                                <td>₦{{ number_format($sale->transfer) }}</td>
                                <td>₦{{number_format( $sale->wallet) }}</td>
                                <td>₦{{number_format( $sale->credit) }}</td>
                                <td><strong>₦{{number_format( $sale->total) }}</strong></td>
                                <td>₦{{number_format( $sale->closing_balance) }}</td>
                                <td>₦{{number_format( $sale->cash_outflow) }}</td>
                                <td><a class="loading-screen" href="{{route('daily-sales.show', $sale->id)}}" class="loader"><i class="bx bxs-show"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        var sales_table = $('#sales-data-table').DataTable({
            lengthChange: true,
            buttons: [{
                    extend: 'excel',
                    title: 'Sales Report'
                },
                {
                    extend: 'pdf',
                    title: 'Sales Report'
                },
                {
                    extend: 'print',
                    title: 'Sales Report',
                    customize: function(win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .prepend('<h4 style="text-align: center;">Sales Report</h4>'); // Add title to the print view
                    }
                }
            ],
            order: [
                [0, 'desc']
            ],

        });

        sales_table.buttons().container().appendTo('#sales-data-table_wrapper .col-md-6:eq(0)');
    });
</script>