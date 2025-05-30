@extends('dashboard.layouts.app')

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
                    <li class="breadcrumb-item active" aria-current="page">Issue out drinks</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

        </div>
    </div>
    <!--end breadcrumb-->

    <div class="row mb-5">
        <div class="col-xl-12 mx-auto">
            <form action="{{ url('store/give-items') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <select required class="form-select" name="outlet_id">
                        <option value="">--Select Bar--</option>
                        @foreach(getModelList('bar-outlets') as $bar)
                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="type" value="Drinks">
                </div>
                <div class="table-responsive">
                    <table id="items-data-table" class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Balance in Stock</th>
                                <th>Quantity to give out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>
                                    <input type="number" class="form-control" name="quantities[{{ $item->id }}]" min="1" max="{{$item->qty}}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                        <label for="recipient">Recipient Name:</label><br>
                        <input type="text" id="recipient" class="form-control" name="recipient">
                    </div>
                    <div class="col-md-6">
                        <label for="recipient">Notes:</label><br>
                        <input type="text" id="recipient" class="form-control" name="note">
                    </div>
                </div>


                <div class="col-lg-12 mb-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Give Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end row-->
@endsection
<script>
    window.addEventListener('load', function() {
        var items_table = $('#items-data-table').DataTable({
            lengthChange: false,
            buttons: ['excel', 'pdf', 'print']
        });

        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');
    });
</script>