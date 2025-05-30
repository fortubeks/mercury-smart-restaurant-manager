<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{url('assets/images/favicon-32x32.png')}}" type="image/png" />
    <!--plugins-->
    <link href="{{url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
    <link href="{{url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="{{url('assets/plugins/notifications/css/lobibox.min.css')}}" />
    <!-- loader-->
    <link href="{{url('assets/css/pace.min.css')}}" rel="stylesheet" />
    <script src="{{url('assets/js/pace.min.js')}}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('assets/css/bootstrap-extended.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{url('assets/css/app.css')}}" rel="stylesheet">
    <link href="{{url('assets/css/icons.css')}}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{url('assets/css/dark-theme.css')}}" />
    <link rel="stylesheet" href="{{url('assets/css/semi-dark.css')}}" />
    <link rel="stylesheet" href="{{url('assets/css/header-colors.css')}}" />
    <title>{{env('APP_NAME')}}</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{url('assets/images/logo.png')}}" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Mercury</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-home-alt'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                    <ul>
                        <li> <a href="index.html"><i class='bx bx-radio-circle'></i>Default</a>
                        </li>
                        <li> <a href="index2.html"><i class='bx bx-radio-circle'></i>Alternate</a>
                        </li>
                        <li> <a href="index3.html"><i class='bx bx-radio-circle'></i>Graphical</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-restaurant"></i>
                        </div>
                        <div class="menu-title">Restaurant</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('orders.index') }}"><i class='bx bx-radio-circle'></i>View Orders</a></li>
                        <li> <a href="{{ route('orders.create') }}"><i class='bx bx-radio-circle'></i>Create Order</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-user"></i>
                        </div>
                        <div class="menu-title">Menu</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('menu-items.index') }}"><i class='bx bx-radio-circle'></i>Menu Items</a></li>
                        <li> <a href="{{ route('menu-items.create') }}"><i class='bx bx-radio-circle'></i>Create Menu Item</a></li>
                        <li> <a href="{{ route('menu-categories.index') }}"><i class='bx bx-radio-circle'></i>Menu Categories</a></li>
                        <li> <a href="{{ route('menu-categories.create') }}"><i class='bx bx-radio-circle'></i>Create Menu Category</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-user"></i>
                        </div>
                        <div class="menu-title">Customers</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('customers.index') }}"><i class='bx bx-radio-circle'></i>All Customers</a></li>
                        <li> <a href="{{ route('customers.create') }}"><i class='bx bx-radio-circle'></i>Create Customer</a></li>

                    </ul>
                </li>
                @role('Accountant')
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-money"></i></div>
                        <div class="menu-title">Audit/Accounting</div>
                    </a>
                    <ul>

                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-detail"></i></div>
                        <div class="menu-title">Expenses</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('expenses.index') }}"><i class='bx bx-radio-circle'></i>Dashboard</a></li>
                        <li>
                            <a href="{{ route('expenses.create') }}"><i class='bx bx-radio-circle'></i>Create</a>
                        </li>
                        <li>
                            <a href="{{ route('expense-categories.index') }}"><i class='bx bx-radio-circle'></i>Expense Categories</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-shopping-bag"></i></div>
                        <div class="menu-title">Purchases</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('purchases.index') }}"><i class='bx bx-radio-circle'></i>Dashboard</a></li>
                        <li> <a href="{{ route('purchases.create') }}"><i class='bx bx-radio-circle'></i>Create</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="">
                        <div class="parent-icon"><i class="bx bx-group"></i></div>
                        <div class="menu-title">Suppliers</div>
                    </a>
                </li>
                @endrole

                @role('Store Keeper')
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-store"></i></div>
                        <div class="menu-title">Store</div>
                    </a>
                    <ul>
                        <li> <a href="{{ route('store.dashboard') }}"><i class='bx bx-radio-circle'></i>Dashboard</a></li>
                        <li> <a href="{{ route('store-items.index') }}"><i class='bx bx-radio-circle'></i>Store Items</a></li>

                        <!-- Add the "Inventories" dropdown -->
                        <li>
                            <a class="has-arrow" href="javascript:;">

                                <i class="bx bx-store"></i> Incoming & Outgoing
                            </a>
                            <ul>
                            </ul>
                        </li>
                    </ul>
                </li>
                @endrole

                @role('Manager')
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-bed"></i>
                        </div>
                        <div class="menu-title">Marketing & Communication</div>
                    </a>
                    <ul>

                    </ul>
                </li>
                @endrole
                @role('Manager')
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-cog"></i>
                        </div>
                        <div class="menu-title">Property Settings</div>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('users.index') }}" class="">
                                <div class="parent-icon"><i class="bx bx-user"></i></div>
                                <div class="menu-title">Users</div>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('settings.index') }}" class="">
                                <div class="parent-icon"><i class='bx bx-info-circle'></i></div>
                                <div class="menu-title">Settings</div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="parent-icon"><i class="bx bx-door-open"></i>
                                </div>
                                <div class="menu-title">Actions Log</div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                <li>
                    <a href="https://wa.me/2348090839412" target="_blank">
                        <div class="parent-icon"><i class="bx bx-support"></i>
                        </div>
                        <div class="menu-title">Support</div>
                    </a>
                </li>

                <li>
                    <a href="#" target="_blank">
                        <div class="parent-icon"><i class="bx bx-video"></i>
                        </div>
                        <div class="menu-title">Tutorials</div>
                    </a>
                </li>

            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand gap-3">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>

                    <div class="position-relative search-bar d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                        <input class="form-control px-5" disabled type="search" placeholder="Search">
                        <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
                    </div>


                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <li class="nav-item mobile-search-icon d-flex d-lg-none d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                                <a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown dropdown-laungauge d-none">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown"><img src="{{url('assets/images/county/02.png')}}" width="22" alt="">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">

                                </ul>
                            </li>
                            <li class="nav-item mobile-search-icon d-flex d-none d-lg-block" style="margin-right: 200px;">
                                <h4 class="form-control mt-2"> <span id="current-date"></span></h4>
                            </li>
                            <li class="nav-item mobile-search-icon d-flex"><span>Current Shift</span>
                                <input id="current_shift" type="date" class="form-control datepicker flatpickr-input active"
                                    name="current_shift" data-max-date="{{ now()->format('Y-m-d') }}" data-toggle="flatpickr" value="{{auth()->user()->current_shift ?? now()->format('Y-m-d')}}" placeholder="Shift">
                            </li>
                            <li class="nav-item dark-mode d-none d-sm-flex">
                                <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown dropdown-app d-none">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i class='bx bx-grid-alt'></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-0">
                                    <div class="app-container p-2 my-2">
                                        <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">


                                        </div><!--end row-->

                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">1</span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-badge">0 New</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">

                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">
                                            <button class="btn btn-primary w-100">View All Notifications</button>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large d-none">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
                                    <i class='bx bx-shopping-bag'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">My Cart</p>
                                            <p class="msg-header-badge">10 Items</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">

                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <h5 class="mb-0">Total</h5>
                                                <h5 class="mb-0 ms-auto">$489.00</h5>
                                            </div>
                                            <button class="btn btn-primary w-100">Checkout</button>
                                        </div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown px-3">
                        @php
                        $user = Auth::user();
                        @endphp
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret show"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if ($user->photo)
                            <img src="{{ asset('storage/hotel/users/photo/' . $user->photo) }}" class="user-img"
                                alt="user avatar">
                            @else
                            <img src="{{ url('assets/images/avatars/avatar-1.png') }}" class="user-img"
                                alt="user avatar">
                            @endif
                            <div class="user-info">
                                <div class="user-info">
                                    <p class="user-name mb-0">{{ $user->name }}</p>
                                    <p class="designattion mb-0">{{ $user->role->name }}</p>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('profile.edit') }}"><i
                                        class="bx bx-user fs-5"></i><span>Profile</span></a>
                            </li>

                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
                            </li>
                            @role('Super Admin')
                            <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
                            </li>
                            @foreach(restaurants() as $restaurant)
                            <div class="dropdown-divider mb-0"></div>
                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center hotel-selector"
                                    href="javascript:void(0);"
                                    data-hotel-id="{{$restaurant->id}}">
                                    <i class="bx bx-home-circle fs-5"></i>
                                    <span>{{$restaurant->name}}</span>
                                </a>
                            </li>
                            @endforeach

                            <!-- Hidden Form for Submitting Restaurant Change -->
                            <form id="change-hotel-form" action="#" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="hotel_id" id="hotel-id-input" value="">
                            </form>
                            @endrole
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bx bx-log-out-circle"></i><span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        @include('rocker-theme.layouts.notifications.flash-messages')
        @yield('content')
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © {{now()->year}}. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->


    <!-- search modal -->

    <!-- end search modal -->




    <!--start switcher-->

    <!--end switcher-->
    <!-- Bootstrap JS -->
    <script src="{{url('assets/js/bootstrap.bundle.min.js')}}"></script>
    <!--plugins-->
    <script src="{{url('assets/js/jquery.min.js')}}"></script>
    <script src="{{url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
    <script src="{{url('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
    <script src="{{url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
    <script src="{{url('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{url('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{url('assets/plugins/chartjs/js/chart.js')}}"></script>
    <!-- <script src="{{url('assets/js/index.js')}}"></script> -->
    <!--app JS-->
    <script src="{{url('assets/js/app.js')}}"></script>

    <script src="{{url('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>


    <!--notification js -->
    <script src="{{url('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
    <script src="{{url('assets/plugins/notifications/js/notifications.min.js')}}"></script>
    <!-- <script>
        new PerfectScrollbar(".app-container")
    </script> -->
    <script src="{{url('assets/js/helper.js')}}"></script>

    <script>
        window.addEventListener('load', function() {
            $(".dropdown-toggle").dropdown();
            $('input').click(function() {
                this.select();
            });

            $(".money").each(function() {
                let value = $(this).text().trim(); // Get the text inside the td
                if ($.isNumeric(value)) { // Check if it's a number
                    let formattedValue = new Intl.NumberFormat('en-NG', {
                        style: 'currency',
                        currency: 'NGN'
                    }).format(value);
                    $(this).text(formattedValue); // Set the formatted value back
                }
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{url('assets/plugins/select2/js/select2-custom.js')}}"></script>

    <script>
        $(".datepicker").flatpickr();

        $(".time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-time").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-format").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-range").flatpickr({
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-inline").flatpickr({
            inline: true,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
        $('#current_shift').change(function() {
            // Get the selected date value
            var selectedDate = $(this).val();

            // Send a POST request to the controller
            $.ajax({
                url: "{{ url('shift/set') }}",
                method: 'POST',
                data: {
                    shift_date: selectedDate,
                    _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                },
                success: function(response) {
                    // Reload the current page
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle errors if needed
                }
            });
        });
        setInterval(function() {
            var currentDate = new Date();
            var options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true
            };
            var formattedDate = currentDate.toLocaleString('en-NG', options);
            $('#current-date').text(formattedDate);
        }, 1000); // Update every second
        window.addEventListener('load', function() {

            $('#outlet').change(function() {
                // Get the selected value
                var selectedOutletId = $(this).val();

                // Send an AJAX GET request to update session value
                $.ajax({
                    url: "{{ url('set-outlet') }}",
                    method: 'post',
                    data: {
                        outlet_id: selectedOutletId,
                        _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                    },
                    success: function(response) {
                        window.location.reload(); // Reload the page after successful update
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        // Handle errors if needed
                    }
                });
            });
        });
    </script>
    <script>
        // Disable scroll wheel
        document.addEventListener('wheel', function(e) {
            if (document.activeElement.type === 'number') {
                document.activeElement.blur();
            }
        });

        // Optional: disable arrow keys (up/down)
        document.addEventListener('keydown', function(e) {
            if (
                document.activeElement.type === 'number' &&
                (e.key === 'ArrowUp' || e.key === 'ArrowDown')
            ) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>