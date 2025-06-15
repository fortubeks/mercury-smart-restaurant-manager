@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="card-title">Perform New Audit</h5>
                        <hr>
                        <form action="{{ route('daily-sales.store') }}" id="sales-form" method="post">
                            @csrf
                            <div class="row mb-5 d-flex justify-content-center">
                                <div class=" col-4 mb-3">
                                    <label class="form-label text-center">Date</label>
                                    <input type="date" id="datepicker" class="form-control @error('date') is-invalid @enderror datepicker flatpickr-input active"
                                        name="shift_date" data-toggle="flatpickr" value="{{$current_audit_date}}"
                                        class="form-control date flatpickr-input active" readonly="readonly"
                                        @isset($current_audit_date)
                                        data-min-date="{{ $current_audit_date }}" data-max-date="{{ $current_audit_date }}"
                                        @endisset>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col">

                                </div>
                                <div class="col">
                                    <h6>Cash</h6>
                                </div>
                                <div class="col">
                                    <h6>POS</h6>
                                </div>
                                <div class="col">
                                    <h6>Transfer</h6>
                                </div>
                                <div class="col">
                                    <h6>Wallet</h6>
                                </div>
                                <div class="col">
                                    <h6>Credit</h6>
                                </div>
                                <div class="col">
                                    <h6>Total</h6>
                                </div>
                            </div>
                            @php
                            $total_cash = $total_pos = $total_transfer = $total_wallet = $total_credit = $grand_total_sales = 0;
                            @endphp
                            @foreach($sales as $key => $sale)
                            @php
                            $cash = $sale['methods']['cash']['total_amount'] ?? 0;
                            $pos = $sale['methods']['pos']['total_amount'] ?? 0;
                            $transfer = $sale['methods']['transfer']['total_amount'] ?? 0;
                            $wallet = $sale['methods']['wallet']['total_amount'] ?? 0;
                            $credit = $sale['methods']['credit']['total_amount'] ?? 0;
                            $total = $sale['total'] ?? 0;

                            $total_cash += $cash;
                            $total_pos += $pos;
                            $total_transfer += $transfer;
                            $total_wallet += $wallet;
                            $total_credit += $credit;
                            $grand_total_sales += $total;
                            @endphp
                            <div class="row mb-2">
                                <div class="col">
                                    <h6>{{$sales[$key]['outlet_name']}}</h6>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="number" readonly class="form-control" name="outlet_cash[]" value="{{ isset($sales[$key]['methods']['cash']) ? $sales[$key]['methods']['cash']['total_amount'] : 0 }}" placeholder="Cash">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="number" readonly class="form-control" name="outlet_pos[]" value="{{ isset($sales[$key]['methods']['pos']) ? $sales[$key]['methods']['pos']['total_amount'] : 0 }}" placeholder="POS">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="number" readonly class="form-control" name="outlet_transfer[]" value="{{ isset($sales[$key]['methods']['transfer']) ? $sales[$key]['methods']['transfer']['total_amount'] : 0 }}" placeholder="Transfer">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="number" readonly class="form-control" name="outlet_wallet[]" value="{{ isset($sales[$key]['methods']['wallet']) ? $sales[$key]['methods']['wallet']['total_amount'] : 0 }}" placeholder="Wallet">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="number" readonly class="form-control" name="outlet_credit[]" value="{{ isset($sales[$key]['methods']['credit']) ? $sales[$key]['methods']['credit']['total_amount'] : 0 }}" placeholder="Credit">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <input type="hidden" name="outlet_id[]" value="{{$key}}">
                                        <input type="number" class="form-control" name="outlet_total[]" value="{{$sale['total']}}" placeholder="Total">
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- Totals Row --}}
                            <div class="row mt-4">
                                <div class="col">
                                    <h6><strong>Totals</strong></h6>
                                </div>
                                <div class="col">
                                    <input type="number" readonly class="form-control" name="total_cash" value="{{ $total_cash }}" placeholder="Total Cash">
                                </div>
                                <div class="col">
                                    <input type="number" readonly class="form-control" name="total_pos" value="{{ $total_pos }}" placeholder="Total POS">
                                </div>
                                <div class="col">
                                    <input type="number" readonly class="form-control" name="total_transfer" value="{{ $total_transfer }}" placeholder="Total Transfer">
                                </div>
                                <div class="col">
                                    <input type="number" readonly class="form-control" name="total_wallet" value="{{ $total_wallet }}" placeholder="Total Wallet">
                                </div>
                                <div class="col">
                                    <input type="number" readonly class="form-control" name="total_credit" value="{{ $total_credit }}" placeholder="Total Credit">
                                </div>
                                <div class="col">
                                    <input type="number" readonly class="form-control" name="grand_total_sales" value="{{ $grand_total_sales }}" placeholder="Grand Total">
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                <hr>
                                <div class="col-12 d-flex align-items-center fw-bold">
                                    <h6 class="me-3">Grand Total:</h6>
                                    <p class="mb-1">
                                        <span>Total Sales:</span>
                                        <span class="ms-1 text-primary">{{ number_format($grand_total_sales, 2) }}</span>

                                        <span class="mx-2">+</span>

                                        <span>Total Settlement:</span>
                                        <span class="ms-1 text-success">{{ number_format(0, 2) }}</span>

                                        <span class="mx-2">+</span>

                                        <span>Total Deposits:</span>
                                        <span class="ms-1 text-warning">{{ number_format(0, 2) }}</span>

                                        <span class="mx-2">=</span>

                                        <span class="text-dark border-bottom border-2">{{ number_format($grand_total_sales, 2) }}</span>
                                        <input value="{{$grand_total_sales}}" name="total" type="hidden">
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">
                                @php
                                $closingBalance = $total_cash + $previousDayCashBalance - $totalCashOutflows;
                                @endphp
                                <hr>
                                <div class="col-12 d-flex align-items-center fw-bold">
                                    <h6 class="me-3">Expected Closing Cash Balance:</h6>
                                    <p class="mb-1">
                                        <span>Total Cash Received:</span>
                                        <span class="ms-1 text-primary">{{ number_format($total_cash, 2) }}</span>

                                        <span class="mx-2">+</span>

                                        <span>Previous Day Cash Balance:</span>
                                        <span class="ms-1 text-success">{{ number_format($previousDayCashBalance, 2) }}</span>

                                        <span class="mx-2">-</span>

                                        <span>Cash Outflow:</span>
                                        <span class="ms-1 text-danger">{{ number_format($totalCashOutflows, 2) }}</span>

                                        <span class="mx-2">=</span>

                                        <span class="text-dark border-bottom border-2">{{ number_format($closingBalance, 2) }}</span>
                                        <input value="{{$closingBalance}}" name="expected_cash_balance" type="hidden">
                                        <input value="{{$previousDayCashBalance}}" name="opening_balance" type="hidden">
                                        <input value="{{$totalCashOutflows}}" name="cash_outflow" type="hidden">
                                    </p>
                                </div>
                                <div class="col-4 d-flex">
                                    <input type="number" class="form-control" name="closing_balance" placeholder="Actual Cash Balance">
                                </div>
                            </div>

                            <div class="row mb-3 mt-3">

                                <!-- <div class="col-3">
                                <textarea class="form-control" name="notes" placeholder="Add note if any" id="" cols="5"
                                    rows="2">{{ old('notes') }}</textarea>
                            </div> -->
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary btn-sm" type="button" class="ms-3 delete-sale" data-bs-toggle="modal" data-bs-target="#submitModal"><i class="bx bxs-save"></i>Save Audit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('rocker-theme.daily-sales.partials.create-summary')

    </div>


    <div class="modal fade" id="submitModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Sales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to sign off on this sales audit?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn-submit">Yes, Confirmed</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        //document.getElementById('loading-screen').style.display = 'none';
        var restaurant_table = $('#restaurant-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });

        var credit_table = $('#credit-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });

        restaurant_table.buttons().container().appendTo('#restaurant-data-table_wrapper .col-md-6:eq(0)');
        credit_table.buttons().container().appendTo('#credit-data-table_wrapper .col-md-6:eq(0)');

        // Get the minDate from the dataset
        var minDate = $("#datepicker").data("mindate");

        // Calculate the maxDate by adding 1 days to the minDate
        var maxDate = new Date(minDate);
        var currentDate = new Date(minDate);
        maxDate.setDate(maxDate.getDate() + 1);
        // Initialize flatpickr
        flatpickr("#datepicker", {
            minDate: minDate,
            maxDate: currentDate,
            dateFormat: "Y-m-d",
            defaultDate: currentDate, // Set the default date
            allowInput: false, // Disable manual input
        });
        $('.btn-submit').click(function() {
            $('#sales-form').submit();
        });

        //$('input[type="number"]').prop('readonly', true);
    });
</script>