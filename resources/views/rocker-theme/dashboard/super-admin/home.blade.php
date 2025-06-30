<form id="filterForm" method="GET" action="{{ route('dashboard') }}">
    <div class="d-flex align-items-center mb-4 gap-3">
        <div><input type="date" class="form-control datepicker flatpickr-input active"
                name="start_date" data-toggle="flatpickr" value="{{ request('start_date') }}"></div>
        <div><input type="date" class="form-control datepicker flatpickr-input active"
                name="end_date" data-max-date="{{ now()->format('Y-m-d') }}" data-toggle="flatpickr" value="{{ request('end_date') }}"></div>
        <div><button type="submit" class="btn btn-white ms-2"><i class="bx bx-refresh me-0"></i></button></div>
    </div>
</form>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Sales</h5>
    </div>
    <div class="card-body">
        <div class="chart-container-9">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <h5>Today </h5>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
            <div class="col">
                <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary font-14">Sales</p>
                                <h5 class="my-0">{{formatCurrency($kpiMetrics['todayOrders'])}}</h5>
                            </div>
                            <div class="text-primary ms-auto font-30"><i class="bx bx-cart-alt"></i>
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
                                <p class="mb-0 text-secondary font-14">Customers</p>
                                <h5 class="my-0">{{$kpiMetrics['todayCustomers']}}</h5>
                            </div>
                            <div class="text-danger ms-auto font-30"><i class="bx bx-dollar"></i>
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
                                <p class="mb-0 text-secondary font-14">Orders</p>
                                <h5 class="my-0">{{$kpiMetrics['todayOrdersCount']}}</h5>
                            </div>
                            <div class="text-success ms-auto font-30"><i class="bx bx-group"></i>
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
                                <p class="mb-0 text-secondary font-14">Deliveries</p>
                                <h5 class="my-0">{{$kpiMetrics['todayDeliveries']}}</h5>
                            </div>
                            <div class="text-warning ms-auto font-30"><i class="bx bx-beer"></i>
                            </div>
                        </div>
                    </div>
                    <div class="mt-1" id="chart7"><canvas width="158" height="25" style="display: inline-block; width: 158px; height: 25px; vertical-align: top;"></canvas></div>
                    <div class="position-absolute end-0 bottom-0 m-2"><span class="text-danger">-14%</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Expenses</h5>
    </div>
    <div class="card-body">
        <div class="chart-container-9">
            <canvas id="expensesChart"></canvas>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        // Get data from Blade variables
        const months = @json($salesData['months']);
        const salesData = @json($salesData['salesData']);

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Sales (NGN) Last 12 Months',
                    data: salesData,
                    backgroundColor: '#008cff',
                    borderColor: '#008cff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const expensesMonths = @json($expensesData['months']);
        const expensesData = @json($expensesData['expensesData']);

        const ctx2 = document.getElementById('expensesChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: expensesMonths,
                datasets: [{
                    label: 'Expenses (NGN) Last 12 Months',
                    data: expensesData,
                    backgroundColor: '#008cff',
                    borderColor: '#008cff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    });
</script>