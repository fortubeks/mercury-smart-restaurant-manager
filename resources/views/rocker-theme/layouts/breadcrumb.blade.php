<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{$title ? $title : ""}}</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('customers.create') }}" class="btn btn-dark"><i class="bx bx-user-plus"></i>Add New Customer</a>
    </div>
</div>