@extends('rocker-theme.layouts.app')
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->

        <!--end breadcrumb-->

        <div class="row mb-5">
            <div class="col-xl-12 mx-auto">
                <form action="{{ url('store/give-items') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select required class="form-select" name="outlet_id">
                            <option value="">--Select Outlet--</option>
                            @foreach(getModelList('restaurant-outlets') as $outlet)
                            <option value="{{$outlet->id}}">{{$outlet->name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="type" value="food">
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
                                @foreach($storeItems as $storeItem)
                                <tr>
                                    <td>{{ $storeItem->code }}</td>
                                    <td>{{ $storeItem->name }}</td>
                                    <td>{{ $storeItem->qty }}</td>
                                    <td>
                                        <input type="number" class="form-control" name="quantities[{{ $storeItem->id }}]" min="1" max="{{$storeItem->qty}}">
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
</div>
<script>
    window.addEventListener('load', function() {
        var items_table = $('#items-data-table').DataTable({
            lengthChange: false,
        });

        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');
    });
</script>