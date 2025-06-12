@extends('dashboard.layouts.app')
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        margin: 20px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h2 {
        margin: 0;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin-top: 20px;
        border-bottom: 1px solid #000;
        padding-bottom: 5px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .table,
    .table th,
    .table td {
        border: 1px solid black;
    }

    .table th,
    .table td {
        padding: 8px;
        text-align: right;
    }

    .footer {
        margin-top: 30px;
        font-size: 12px;
        text-align: center;
        color: gray;
    }

    h3 {
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
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
                    <li class="breadcrumb-item active" aria-current="page">Sales</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="section-title">Income & Revenue</div>
            <table class="table">
                <tr>
                    <th>Sales Point</th>
                    <th>Total (NGN)</th>
                </tr>
                <tr>
                    <td>Accomodation</td>
                    <td>{{ formatCurrency($accomodationSales) }}</td>
                </tr>
                <tr>
                    <td>Total Operating Income</td>
                    <td>{{ number_format($profitLossData['operating_income'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Cost of Goods Sold</div>
            <table class="table">
                <tr>
                    <td>Total Cost of Goods Sold</td>
                    <td>{{ number_format($profitLossData['cost_of_goods_sold'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Gross Profit</div>
            <table class="table">
                <tr>
                    <td>Gross Profit</td>
                    <td>{{ number_format($profitLossData['gross_profit'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Operating Expense</div>
            <table class="table">
                <tr>
                    <td>Total Operating Expense</td>
                    <td>{{ number_format($profitLossData['operating_expense'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Operating Profit</div>
            <table class="table">
                <tr>
                    <td>Operating Profit</td>
                    <td>{{ number_format($profitLossData['operating_profit'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Non-Operating Income</div>
            <table class="table">
                <tr>
                    <td>Total Non-Operating Income</td>
                    <td>{{ number_format($profitLossData['non_operating_income'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Non-Operating Expense</div>
            <table class="table">
                <tr>
                    <td>Total Non-Operating Expense</td>
                    <td>{{ number_format($profitLossData['non_operating_expense'], 2) }}</td>
                </tr>
            </table>

            <div class="section-title">Net Profit/Loss</div>
            <table class="table">
                <tr>
                    <td><strong>Net Profit/Loss</strong></td>
                    <td><strong>{{ number_format($profitLossData['net_profit_loss'], 2) }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="title">{{ __('P & L ') }} </h5>
        </div>
        @php
        $foodProfit = $restaurantSales - $foodAndDrinks['foodPurchases'];
        $drinkProfit = $barSales - $foodAndDrinks['drinkPurchases'];
        $accomodationProfit = $accomodationSales - $totalExpenses;
        $totalProfit = $accomodationProfit + $foodProfit + $drinkProfit;
        $totalRevenue = $accomodationSales + $barSales + $restaurantSales;
        @endphp
        <div class="card-body">
            <table class="table mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Revenue</th>
                        <th scope="col">Expenses</th>
                        <th scope="col">Purchases</th>
                        <th scope="col">Profit</th>
                        <th scope="col">Stock Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Accomodation</th>
                        <td>{{ formatCurrency($accomodationSales) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Food</th>
                        <td>{{ formatCurrency($restaurantSales) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency($foodAndDrinks['foodPurchases']) }}</td>
                        <td>{{ formatCurrency($foodProfit) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Drinks</th>
                        <td>{{ formatCurrency($barSales) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency($foodAndDrinks['drinkPurchases']) }}</td>
                        <td>{{ formatCurrency($drinkProfit) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Others</th>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Total</th>
                        <td>{{ formatCurrency($totalRevenue) }}</td>
                        <td>{{ formatCurrency($totalExpenses) }}</td>
                        <td>{{ formatCurrency($foodAndDrinks['totalPurchases']) }}</td>
                        <td @if($totalProfit> 0) echo 'class="text-success"' @else echo 'class="text-danger"' @endif>{{ formatCurrency($totalProfit) }}</td>
                        <td>{{ formatCurrency(0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="title">{{ __('Receivables') }} </h5>
        </div>

        <div class="card-body">
            <table class="table mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Cash</th>
                        <th scope="col">POS</th>
                        <th scope="col">Transfer</th>
                        <th scope="col">Outstanding Debts </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ formatCurrency($receivables['cash']) }}</td>
                        <td>{{ formatCurrency($receivables['pos']) }}</td>
                        <td>{{ formatCurrency($receivables['transfer']) }}</td>
                        <td><a class="loader" href="{{route('debtors')}}">Debtors</a></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-7">
            <div class="card radius-10">
                <div class="card-header">
                    <h5 class="title">{{ __('KPIs') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-3">
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">Average daily rate (ADR)</p>
                                            <h5 class="my-0">{{formatCurrency($kpis['adr'])}}</h5>
                                        </div>
                                        <div class="text-primary ms-auto font-30"><i class="bx bx-money"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart4"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-success">+25%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">Occupancy Rate</p>
                                            <h5 class="my-0">{{$kpis['opr']}}</h5>
                                        </div>
                                        <div class="text-danger ms-auto font-30"><i class="bx bx-hotel"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart5"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-success">+15%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">ALOS (Average Length of Stay)</p>
                                            <h5 class="my-0">{{$kpis['alos']}}</h5>
                                        </div>
                                        <div class="text-success ms-auto font-30"><i class="bx bx-hotel"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart6"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-danger">-10%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">Revenue Per Available Rooms</p>
                                            <h5 class="my-0">{{formatCurrency($kpis['revPar'])}}</h5>
                                        </div>
                                        <div class="text-warning ms-auto font-30"><i class="bx bx-hotel"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart7"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-danger">-14%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">ARR (Average Room Rate)</p>
                                            <h5 class="my-0">{{formatCurrency($kpis['arr'])}}</h5>
                                        </div>
                                        <div class="text-info ms-auto font-30"><i class="bx bx-hotel"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart8"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-success">+28%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">Total Bookings</p>
                                            <h5 class="my-0">{{$kpis['bookings']}}</h5>
                                        </div>
                                        <div class="ms-auto font-30"><i class="bx bx-group"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart9"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-success">+35%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">Average Daily Bookings</p>
                                            <h5 class="my-0">{{$kpis['avgDailyBookings']}}</h5>
                                        </div>
                                        <div class="ms-auto font-30"><i class="bx bx-group"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart9"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-success">+35%</span></div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <p class="mb-0 text-secondary font-14">Total Guests</p>
                                            <h5 class="my-0">{{count(getModelList('guests'))}}</h5>
                                        </div>
                                        <div class="ms-auto font-30"><i class="bx bx-group"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-1" id="chart9"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                                <div class="position-absolute end-0 bottom-0 m-2"><span class="text-success">+35%</span></div>
                            </div>
                        </div>
                    </div><!--end row-->
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h6>Top Guests</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Guest</th>
                                    <th>Visits</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSpendingGuests as $guest)
                                <tr>
                                    <td class="">{{$guest['guest_name']}}</td>
                                    <td class="text-left"></td>
                                    <td class="total">{{formatCurrency($guest['total_spending'])}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6>Top Selling Food Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSellingFoodItems as $item)
                                <tr>
                                    <td class="">{{$item['item_name']}}</td>
                                    <td class="text-left">{{$item['total_qty']}}</td>
                                    <td class="total">{{formatCurrency($item['total_amount_sold'])}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6>Top Selling Drink Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSellingDrinkItems as $item)
                                <tr>
                                    <td class="">{{$item['item_name']}}</td>
                                    <td class="text-left">{{$item['total_qty']}}</td>
                                    <td class="total">{{formatCurrency($item['total_amount_sold'])}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
</div>
<div class="modal  fade" id="sales" tabindex="-1" aria-labelledby="deleteCartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Sales </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="accomodation-data-table" class="table table-striped table-bordered">
                        <thead>
                            <th> Date</th>
                            <th> Cash</th>
                            <th> POS</th>
                            <th> Transfer</th>
                            <th> Wallet</th>
                            <th> Credit</th>
                            <th>Total</th>

                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                            <tr>
                                <td><a href="{{route('daily-sales.show', $sale->id)}}">{{ $sale->shift_date }}</a></td>
                                <td>{{formatCurrency($sale->totalCash())}}</td>
                                <td>{{formatCurrency($sale->totalPos())}}</td>
                                <td>{{formatCurrency($sale->totalTransfer())}}</td>
                                <td>{{formatCurrency($sale->totalWallet())}}</td>
                                <td>{{formatCurrency($sale->totalCredit())}}</td>
                                <td>{{formatCurrency($sale->final_total)}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

    });
</script>

@endsection