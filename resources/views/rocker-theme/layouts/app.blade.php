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
                        <!-- @role('Manager')
                        <li> <a href="{{ route('restaurant.sales.dashboard') }}"><i class='bx bx-radio-circle'></i>Dashboard</a></li>
                        <li>
                            <a href="{{ route('restaurant-items.create') }}"><i class='bx bx-radio-circle'></i>Add Items</a>
                        </li>
                        <li>
                            <a href="{{ route('restaurant-items.index') }}"><i class='bx bx-radio-circle'></i>Menu Items</a>
                        </li>
                        <li>
                            <a href="{{ url('map-restaurant-menu-items') }}"><i class='bx bx-radio-circle'></i>Map Items</a>
                        </li>
                        <li><a href="{{ url('restaurant-import-items') }}"><i class='bx bx-radio-circle'></i>Import Menu Items</a></li>
                        @endrole('Manager') -->
                    </ul>
                </li>
                @role('accounts')
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class="bx bx-money"></i></div>
                        <div class="menu-title">Audit/Accounting</div>
                    </a>
                    <ul>
                        <!-- <li> <a class="loading-screen" href="{{ route('daily-sales.create') }}"><i class='bx bx-radio-circle'></i>New Audit</a></li>
                        <li><a href="{{ route('daily-sales.index') }}"><i class='bx bx-radio-circle'></i>View Audits</a></li>
                        <li><a class="loading-screen" href="{{ url('sales-dashboard') }}"><i class='bx bx-radio-circle'></i>Sales Dashboard</a></li>
                        <li><a href="{{ route('bank-accounts.index') }}"><i class='bx bx-radio-circle'></i>Bank Accounts</a></li>
                        <li><a class="loading-screen" href="{{ route('payments.index') }}"><i class='bx bx-radio-circle'></i>Incoming Payments</a></li>
                        <li><a href="{{ route('outgoing-payments.index') }}"><i class='bx bx-radio-circle'></i>Outgoing Payments</a></li>
                        <li><a href="{{ url('#') }}"><i class='bx bx-radio-circle'></i>Creditors </a></li>
                        <li><a class="loading-screen" href="{{ url('debtors') }}"><i class='bx bx-radio-circle'></i>Debtors </a></li>
                        <li><a href="{{ route('reports.index') }}"><i class='bx bx-radio-circle'></i>Reports</a></li> -->
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
                        <li> <a href="{{ route('purchases.index') }}"><i class='bx bx-radio-circle'></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('purchases.create') }}"><i class='bx bx-radio-circle'></i>Create</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}" class="">
                        <div class="parent-icon"><i class="bx bx-group"></i></div>
                        <div class="menu-title">Suppliers</div>
                    </a>
                </li>
                @endrole

                @role('store')
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-store"></i></div>
                        <div class="menu-title">Store</div>
                    </a>
                    <ul>
                        <!-- <li><a href="{{ route('stores.index') }}"><i class='bx bx-radio-circle'></i>Store</a></li> -->
                        <!-- <li><a href="{{ url('store-items') }}"><i class='bx bx-radio-circle'></i>Store Items</a></li>
                        <li><a href="{{ url('store/give-items?type=food') }}"><i class='bx bx-radio-circle'></i>Give Out Food Items</a></li>
                        <li><a href="{{ url('store/give-items?type=drinks') }}"><i class='bx bx-radio-circle'></i>Give Out Drinks</a></li>
                        <li><a href="{{ url('store/give-items?type=hk') }}"><i class='bx bx-radio-circle'></i>Give Out Housekeeping Items</a></li>
                        <li><a href="{{ url('store/migrate-items?type=drinks') }}"><i class='bx bx-radio-circle'></i>Migrate Drink Items</a></li>
                        <li><a href="{{ url('store/import-items') }}"><i class='bx bx-radio-circle'></i>Import Items</a></li>
                        <li><a href="{{ route('item-subcategory.index') }}"><i class='bx bx-radio-circle'></i>Store Items Subcategory</a></li>
                        <li><a href="{{ url('store/stock-count') }}"><i class='bx bx-radio-circle'></i>Stock Count</a></li> -->
                        <!-- Add the "Inventories" dropdown -->
                        <li>
                            <a class="has-arrow" href="javascript:;">

                                <i class="bx bx-store"></i> Incoming & Outgoing
                            </a>
                            <ul>
                                <!-- <li><a href="{{ route('store.incoming-inventories') }}">Incoming Items</a></li>
                                <li><a href="{{ route('store.outgoing-inventories') }}">Outgoing Items</a></li> -->
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
                @role('manager')
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
                            <a href="{{ route('logs.index') }}">
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
                    <a href="{{ route('tutorials.show') }}" target="_blank">
                        <div class="parent-icon"><i class="bx bx-video"></i>
                        </div>
                        <div class="menu-title">Tutorials</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-cart'></i>
                        </div>
                        <div class="menu-title">eCommerce</div>
                    </a>
                    <ul>
                        <li> <a href="ecommerce-products.html"><i class='bx bx-radio-circle'></i>Products</a>
                        </li>
                        <li> <a href="ecommerce-products-details.html"><i class='bx bx-radio-circle'></i>Product Details</a>
                        </li>
                        <li> <a href="ecommerce-add-new-products.html"><i class='bx bx-radio-circle'></i>Add New Products</a>
                        </li>
                        <li> <a href="ecommerce-orders.html"><i class='bx bx-radio-circle'></i>Orders</a>
                        </li>
                    </ul>
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

                    <div class="position-relative search-bar d-lg-block d-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                        <input class="form-control px-5" disabled type="search" placeholder="Search">
                        <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-5"><i class='bx bx-search'></i></span>
                    </div>


                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center gap-1">
                            <li class="nav-item mobile-search-icon d-flex d-lg-none" data-bs-toggle="modal" data-bs-target="#SearchModal">
                                <a class="nav-link" href="avascript:;"><i class='bx bx-search'></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown dropdown-laungauge d-none d-sm-flex">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="avascript:;" data-bs-toggle="dropdown"><img src="{{url('assets/images/county/02.png')}}" width="22" alt="">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/01.png')}}" width="20" alt=""><span class="ms-2">English</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/02.png')}}" width="20" alt=""><span class="ms-2">Catalan</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/03.png')}}" width="20" alt=""><span class="ms-2">French</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/04.png')}}" width="20" alt=""><span class="ms-2">Belize</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/05.png')}}" width="20" alt=""><span class="ms-2">Colombia</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/06.png')}}" width="20" alt=""><span class="ms-2">Spanish</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/07.png')}}" width="20" alt=""><span class="ms-2">Georgian</span></a>
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img src="{{url('assets/images/county/08.png')}}" width="20" alt=""><span class="ms-2">Hindi</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item dark-mode d-none d-sm-flex">
                                <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                                </a>
                            </li>




                        </ul>
                    </div>
                    <div class="user-box dropdown px-3">
                        <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret show"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{-- @if (!Auth::user()->photo)
                        <img src="{{ asset('storage/hotel/users/photo/' . Auth::user()->photo) }}" class="user-img"
                            alt="user avatar">
                            @else --}}
                            <img src="{{ env('APP_URL') }}/dashboard/assets/images/avatars/avatar-1.png" class="user-img"
                                alt="user avatar">
                            {{-- @endif --}}
                            <div class="user-info">
                                <div class="user-info">
                                    <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                                    <p class="designattion mb-0">{{ removeUnderscoreAndCapitalise(Auth::user()->role) }}</p>
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item d-flex align-items-center"
                                    href="{{ route('profile.edit') }}"><i
                                        class="bx bx-user fs-5"></i><span>Profile</span></a>
                            </li>

                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('home') }}"><i class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
                            </li>
                            @role('Hotel Owner')
                            <li><a class="dropdown-item d-flex align-items-center" href="{{ route('settings.') }}"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
                            </li>
                            @foreach(hotels() as $hotel)
                            <div class="dropdown-divider mb-0"></div>
                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center hotel-selector"
                                    href="javascript:void(0);"
                                    data-hotel-id="{{$hotel->id}}">
                                    <i class="bx bx-home-circle fs-5"></i>
                                    <span>{{$hotel->name}}</span>
                                </a>
                            </li>
                            @endforeach

                            <!-- Hidden Form for Submitting Hotel Change -->
                            <form id="change-hotel-form" action="{{ route('change.hotel') }}" method="POST" style="display: none;">
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
    <div class="modal" id="SearchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header gap-2">
                    <div class="position-relative popup-search w-100">
                        <input class="form-control form-control-lg ps-5 border border-3 border-primary" type="search" placeholder="Search">
                        <span class="position-absolute top-50 search-show ms-3 translate-middle-y start-0 top-50 fs-4"><i class='bx bx-search'></i></span>
                    </div>
                    <button type="button" class="btn-close d-md-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search-list">
                        <p class="mb-1">Html Templates</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action active align-items-center d-flex gap-2 py-1"><i class='bx bxl-angular fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vuejs fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-magento fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-shopify fs-4'></i>eCommerce Html Templates</a>
                        </div>
                        <p class="mb-1 mt-3">Web Designe Company</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-windows fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-dropbox fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-opera fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-wordpress fs-4'></i>eCommerce Html Templates</a>
                        </div>
                        <p class="mb-1 mt-3">Software Development</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-mailchimp fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-zoom fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-sass fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vk fs-4'></i>eCommerce Html Templates</a>
                        </div>
                        <p class="mb-1 mt-3">Online Shoping Portals</p>
                        <div class="list-group">
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-slack fs-4'></i>Best Html Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-skype fs-4'></i>Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-twitter fs-4'></i>Responsive Html5 Templates</a>
                            <a href="javascript:;" class="list-group-item list-group-item-action align-items-center d-flex gap-2 py-1"><i class='bx bxl-vimeo fs-4'></i>eCommerce Html Templates</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end search modal -->




    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <h6 class="mb-0">Theme Styles</h6>
            <hr />
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
                    <label class="form-check-label" for="lightmode">Light</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
                    <label class="form-check-label" for="darkmode">Dark</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
                    <label class="form-check-label" for="semidark">Semi Dark</label>
                </div>
            </div>
            <hr />
            <div class="form-check">
                <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
            </div>
            <hr />
            <h6 class="mb-0">Header Colors</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator headercolor1" id="headercolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor2" id="headercolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor3" id="headercolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor4" id="headercolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor5" id="headercolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor6" id="headercolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor7" id="headercolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor8" id="headercolor8"></div>
                    </div>
                </div>
            </div>
            <hr />
            <h6 class="mb-0">Sidebar Colors</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script src="{{url('assets/js/index.js')}}"></script>
    <!--app JS-->
    <script src="{{url('assets/js/app.js')}}"></script>
    <script>
        new PerfectScrollbar(".app-container")
    </script>
</body>

</html>