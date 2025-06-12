@extends('dashboard.layouts.app')

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
                    <li class="breadcrumb-item active" aria-current="page">View Sales</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="ms-auto">
                <a href="" class="btn btn-dark">View Sales</a>
            </div> --}}
    </div>
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
                                name="date" data-toggle="flatpickr" value="{{ $daily_sale->shift_date }}"
                                data-max-date="{{$last_audit_date}}" id="shift" data-min-date="{{$first_audit_date}}">
                        </div>
                        @if(hotels()->count() > 1)
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
                                <tr>
                                    <td><strong>Accommodation</strong></td>
                                    <td class="money text-end">{{ $daily_sale->accomodation_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->accomodation_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->accomodation_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->accomodation_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->accomodation_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->accomodation_total }}</td>
                                </tr>


                                <tr>
                                    <td><strong>Bar</strong></td>
                                    <td class="money text-end">{{ $daily_sale->bar_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->bar_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->bar_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->bar_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->bar_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->bar_total }}</td>
                                </tr>

                                <tr>
                                    <td><strong>Restaurant</strong></td>
                                    <td class="money text-end">{{ $daily_sale->restaurant_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->restaurant_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->restaurant_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->restaurant_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->restaurant_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->restaurant_total }}</td>
                                </tr>

                                @activeModule('laundry_active')
                                <tr>
                                    <td><strong>Laundry</strong></td>
                                    <td class="money text-end">{{ $daily_sale->laundry_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->laundry_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->laundry_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->laundry_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->laundry_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->laundry_total }}</td>
                                </tr>
                                @endactiveModule

                                @activeModule('gym_active')
                                <tr>
                                    <td><strong>Gym</strong></td>
                                    <td class="money text-end">{{ $daily_sale->gym_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->gym_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->gym_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->gym_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->gym_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->gym_total }}</td>
                                </tr>
                                @endactiveModule

                                @activeModule('swimming_active')
                                <tr>
                                    <td><strong>Swimming</strong></td>
                                    <td class="money text-end">{{ $daily_sale->swimming_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->swimming_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->swimming_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->swimming_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->swimming_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->swimming_total }}</td>
                                </tr>
                                @endactiveModule
                                @activeModule('venue_active')
                                <tr>
                                    <td><strong>Venue</strong></td>
                                    <td class="money text-end">{{ $daily_sale->venue_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->venue_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->venue_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->venue_wallet }}</td>
                                    <td class="money text-end">{{ $daily_sale->venue_credit }}</td>
                                    <td class="money text-end">{{ $daily_sale->venue_total }}</td>
                                </tr>
                                @endactiveModule


                                @foreach($extra_outlets_sales as $extra_outlet_sale)
                                <tr>
                                    <td><strong>{{ $extra_outlet_sale->outlet->name }}</strong></td>
                                    <td class="money text-end">{{ $extra_outlet_sale->cash }}</td>
                                    <td class="money text-end">{{ $extra_outlet_sale->pos }}</td>
                                    <td class="money text-end">{{ $extra_outlet_sale->transfer }}</td>
                                    <td class="money text-end">{{ $extra_outlet_sale->wallet }}</td>
                                    <td class="money text-end">{{ $extra_outlet_sale->credit }}</td>
                                    <td class="money text-end">{{ $extra_outlet_sale->total }}</td>
                                </tr>
                                @endforeach


                                <tr>
                                    <td><strong>Total Sales</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="money text-end"><strong>{{ $daily_sale->final_total }}</strong></td>
                                </tr>

                                <tr>
                                    <td><strong>Settlements</strong></td>
                                    <td class="money text-end">{{ $daily_sale->settlement_cash }}</td>
                                    <td class="money text-end">{{ $daily_sale->settlement_pos }}</td>
                                    <td class="money text-end">{{ $daily_sale->settlement_transfer }}</td>
                                    <td class="money text-end">{{ $daily_sale->settlement_wallet }}</td>
                                    <td class="money text-end">0</td> <!-- Empty Credit Column -->
                                    <td class="money text-end">{{ $daily_sale->settlement_total }}</td>
                                </tr>
                                <!-- <tr>
                                    <td><strong>Grand Total</strong></td>
                                    <td class="money text-end">{{ $daily_sale->totalCash() }}</td>
                                    <td class="money text-end">{{ $daily_sale->totalPos() }}</td>
                                    <td class="money text-end">{{ $daily_sale->totalTransfer() }}</td>
                                    <td class="money text-end">{{ $daily_sale->totalWallet() }}</td>
                                    <td class="money text-end">{{ $daily_sale->totalCredit() }}</td>
                                    <td class="money text-end"><strong>{{ $daily_sale->grand_total }}</strong></td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <div class="row mb-3 mt-3">
                        <hr>
                        <div class="col-12 d-flex align-items-center fw-bold">
                            <h6 class="me-3">Grand Total:</h6>

                            <span>Total Sales:</span>
                            <span class="ms-1 text-primary">{{ number_format($daily_sale->final_total, 2) }}</span>

                            <span class="mx-2">+</span>

                            <span>Total Settlement:</span>
                            <span class="ms-1 text-success">{{ number_format($daily_sale->settlement_total, 2) }}</span>

                            <span class="mx-2">+</span>

                            <span>Total Deposits:</span>
                            <span class="ms-1 text-warning">{{ number_format($daily_sale->total_deposits, 2) }}</span>

                            <span class="mx-2">+</span>

                            <span>Previous Cash at Hand:</span>
                            <span class="ms-1 text-danger">{{ number_format($previousDayCashAtHand, 2) }}</span>

                            <span class="mx-2">=</span>

                            <span class="text-dark border-bottom border-2">{{ number_format($daily_sale->grand_total, 2) }}</span>
                        </div>
                    </div>

                    <div class="row mb-3 mt-3">
                        <hr>
                        <div class="col-12 d-flex align-items-center fw-bold">
                            <span>Total Cash Outflows:</span>
                            <span class="ms-1 text-danger">{{ number_format($daily_sale->total_outflows, 2) }}</span>

                            <span class="mx-2">,</span>

                            <span>Cash Balance:</span>
                            <span class="ms-1 text-success">{{ number_format($daily_sale->cash_account_balance, 2) }}</span>
                        </div>
                    </div>


                    <div class="d-flex justify-content-end">
                        <!-- <a href="{{route('daily-sales.edit',$daily_sale->id)}}" class="btn btn-primary btn-sm mx-3" type="button" class="ms-3"><i class="bx bxs-save"></i>Update</a> -->
                        @if($is_latest)
                        <button class="btn btn-danger btn-sm" type="button" class="ms-3 delete-sale" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bx bxs-trash"></i>Delete</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dashboard.daily-sales.partials.create-summary')
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
                <form method="POST" action="{{url('daily-sales/'.$daily_sale->id)}}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Update Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to update this sales report?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="submitSalesForm()" class="btn btn-success btn-submit">Yes, Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    function submitSalesForm() {

        document.getElementById('sales-form').submit();
    }
    window.addEventListener('load', function() {

        var accomodation_table = $('#accomodation-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print'],
            order: [
                [1, 'asc']
            ],
        });
        var restaurant_table = $('#restaurant-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });
        var bar_table = $('#bar-data-table').DataTable({
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
                            .prepend('<p style="text-align: left;">Date:{{$daily_sale->shift_date}}</p>')
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
        accomodation_table.buttons().container().appendTo('#accomodation-data-table_wrapper .col-md-6:eq(0)');
        restaurant_table.buttons().container().appendTo('#restaurant-data-table_wrapper .col-md-6:eq(0)');
        bar_table.buttons().container().appendTo('#bar-data-table_wrapper .col-md-6:eq(0)');
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
@endsection