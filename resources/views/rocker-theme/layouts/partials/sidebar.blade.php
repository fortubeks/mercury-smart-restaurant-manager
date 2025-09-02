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
            <a href="{{ route('dashboard') }}" class="">
                <div class="parent-icon"><i class="bx bx-home-circle"></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"> <i class="bx bx-restaurant"></i>
                </div>
                <div class="menu-title">Orders</div>
            </a>
            <ul>
                <li> <a href="{{ route('orders.index') }}"><i class='bx bx-radio-circle'></i>View Orders</a></li>
                <li> <a href="{{ route('orders.create') }}"><i class='bx bx-radio-circle'></i>Create Order</a></li>
            </ul>
        </li>
        @role('Manager')
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"> <i class="bx bx-book"></i>
                </div>
                <div class="menu-title">Menu</div>
            </a>
            <ul>
                <li> <a href="{{ route('menu-items.index') }}"><i class='bx bx-radio-circle'></i>Menu Items</a></li>
                <li> <a href="{{ route('menu-categories.index') }}"><i class='bx bx-radio-circle'></i>Menu Item Categories</a></li>
                <li> <a href="{{ route('menu-items.mapping-form') }}"><i class='bx bx-radio-circle'></i>Map Menu Items</a></li>
                <li> <a href="{{ route('menu-items.import.form') }}"><i class='bx bx-radio-circle'></i>Import Menu Items</a></li>
            </ul>
        </li>
        @endrole
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
                <li> <a class="loading-screen" href="{{ route('daily-sales.create') }}"><i class='bx bx-radio-circle'></i>New Audit</a></li>
                <li><a href="{{ route('daily-sales.index') }}"><i class='bx bx-radio-circle'></i>View Audits</a></li>
                <li><a href="{{ route('bank-accounts.index') }}"><i class='bx bx-radio-circle'></i>Bank Accounts</a></li>
                <li><a class="loading-screen" href="{{ route('incoming-payments.index') }}"><i class='bx bx-radio-circle'></i>Incoming Payments</a></li>
                <li><a href="{{ route('outgoing-payments.index') }}"><i class='bx bx-radio-circle'></i>Outgoing Payments</a></li>
                <li><a href="{{ route('reports.index') }}"><i class='bx bx-radio-circle'></i>Reports</a></li>

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
                <li> <a href="{{ route('stores.index') }}"><i class='bx bx-radio-circle'></i>Dashboard</a></li>
                <li> <a href="{{ route('store-items.index') }}"><i class='bx bx-radio-circle'></i>Store Items</a></li>
                <li> <a href="{{ route('store.give-items') }}"><i class='bx bx-radio-circle'></i>Give Items</a></li>
                <li> <a href="{{ route('store-item.migrate-items') }}"><i class='bx bx-radio-circle'></i>Migrate Items</a></li>
                <li> <a href="#"><i class='bx bx-radio-circle'></i>Manage Item Categories</a></li>
                <!-- Add the "Inventories" dropdown -->
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <i class="bx bx-store"></i> Incoming & Outgoing
                    </a>
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
                <div class="menu-title">Restaurant Settings</div>
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
                    <a href="{{ route('delivery-riders.index') }}" class="">
                        <div class="parent-icon"><i class="bx bx-user"></i></div>
                        <div class="menu-title">Delivery Riders</div>
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
                <div class="parent-icon"><i class="bx bx-support"></i></div>
                <div class="menu-title">Support</div>
            </a>
        </li>

        <li>
            <a href="#" target="_blank">
                <div class="parent-icon"><i class="bx bx-video"></i></div>
                <div class="menu-title">Tutorials</div>
            </a>
        </li>

    </ul>
    <!--end navigation-->
</div>