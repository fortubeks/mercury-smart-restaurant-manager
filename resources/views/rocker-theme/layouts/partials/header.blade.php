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