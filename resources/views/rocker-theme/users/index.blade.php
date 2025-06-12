@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->
        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="position-relative">
                        <h5>Users</h5>
                    </div>
                    <div class="ms-auto"><a href="{{ route('users.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Add User</a></div>
                </div>
                <div class="table-responsive scrollable-table-container">
                    <table id="users-table" class="table mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Phone </th>
                                @if($restaurants->count() > 1)<th>Restaurant</th>@endif
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)

                            <tr>
                                <td>
                                    @if (!empty($user->photo))
                                    <img class="img-fluid user-photo" src="{{url('storage/images/profile_images/' . $user->photo)}}" alt="">
                                    @else
                                    <i class="fadeIn animated bx bx-user"></i>
                                    @endif
                                </td>
                                <td>{{ $user->name }}</br>
                                    <span class="small-font">{{ $user->email }}</span>
                                </td>
                                <td>{{ $user->phone }}</td>
                                @if($restaurants->count() > 1)<td>{{$user->restaurant->name}}</td>@endif
                                <td>{{ $user->role->name }}</td>
                                <td><x-status-text :status="$user->is_active" /></td>
                                <td><span class="small-font text-secondary">{{ $user->last_login ?? 'N/A' }}</span></td>
                                <td>
                                    @if (auth()->user()->hasRole('Manager') && !$user->is_super_admin)
                                    <div class="d-flex order-actions">
                                        {{-- Edit Link --}}
                                        <a href="{{ route('users.edit', $user->id) }}">
                                            <i class='bx bxs-edit'></i>
                                        </a>

                                        @if ($user->role !== 'Hotel_Owner' && $user->id !== auth()->id())
                                        {{-- Delete Icon --}}
                                        <a class="ms-3 delete-user" href="javascript:void(0);" data-resource-id="{{ $user->id }}" data-resource-url="{{ url('users') }}" data-bs-toggle="modal" data-bs-target="#deleteResourceModal">
                                            <i class="bx bxs-trash"></i>
                                        </a>
                                        @endif
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>


<script>
    window.addEventListener('load', function() {
        var user_table = $('#users-table').DataTable({
            lengthChange: false,
        });
        user_table.buttons().container().appendTo('#users-table_wrapper .col-md-6:eq(0)');

    });
</script>