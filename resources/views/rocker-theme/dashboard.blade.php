@extends('rocker-theme.layouts.app')

<div class="page-wrapper">
    <div class="page-content">
        <div class="row">
            <h6>Welcome, {{$user->name}}, you are currently in {{$user->current_shift}} shift. It is a great day for business on Venus</h6>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 mt-4">
            <h6>Quick Access</h6>
        </div>
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            @role('Manager')
            <div class="col">
                <a href="{{ route('users.index') }}" class="card-link">
                    <div class="card radius-10 border-start border-0 border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="my-1 text-info">Users</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bx-user'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endrole

            @role('Waiter')
            <div class="col">
                <a href="{{ route('orders.index') }}" class="card-link">
                    <div class="card radius-10 border-start border-0 border-4 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="my-1 text-warning">Sales & Orders</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                    <i class='bx bx-drink'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endrole
            @role('Accountant')
            <div class="col">
                <a href="{{ url('sales-dashboard') }}" class="card-link">
                    <div class="card radius-10 border-start border-0 border-4 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="my-1 text-info">Sales & Audit</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                    <i class='bx bx-store'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('expenses') }}" class="card-link">
                    <div class="card radius-10 border-start border-0 border-4 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="my-1 text-success">Expenses</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                    <i class='bx bx-store'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('purchases') }}" class="card-link">
                    <div class="card radius-10 border-start border-0 border-4 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="my-1 text-danger">Purchases</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                    <i class='bx bx-store'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endrole
            @role('Store Keeper')
            <div class="col">
                <a href="{{ url('store-items') }}" class="card-link">
                    <div class="card radius-10 border-start border-0 border-4 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="my-1 text-danger">Store</h5>
                                </div>
                                <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                    <i class='bx bx-store'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endrole
        </div><!--end row-->
    </div>
</div>
<script>
    window.addEventListener('load', function() {});
</script>