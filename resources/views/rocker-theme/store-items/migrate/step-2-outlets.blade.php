@extends('rocker-theme.layouts.app')
@section('content')
<h5 class="mb-3">Migrate Items</h5>


<div class="row mb-5">
    <div class="col-xl-12 mx-auto">
        <form id="form" action="{{ url('store-item.migrate-items.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="hidden" name="from_id" value="{{$outletA->id}}">
                <input type="hidden" name="to_id" value="{{$outletB->id}}">
                <input type="hidden" name="from_type" value="outlet">
                <input type="hidden" name="to_type" value="outlet">
            </div>
            <table id="items-data-table" class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Item Code</th>
                        <th>{{$outletA->name}}</th>
                        <th>Quantity to Send</th>
                        <th>{{$outletB->name}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outletAItems as $outletAItem)
                    @php
                    $storeItemId = $outletAItem->store_item_id;
                    $outletBItem = $outletBItems->get($storeItemId);
                    @endphp
                    <tr>
                        <td>{{ $outletAItem->storeItem->code }}</td>
                        <td>{{ $outletAItem->storeItem->name.'('.$outletAItem->qty.')' }}</td>
                        <td>
                            <input type="number" class="form-control" name="quantities[{{ $outletAItem->id }}]" min="1" max="{{ $outletAItem->qty }}">
                        </td>
                        <td>{{ $outletBItem ? $outletBItem->storeItem->name.'('.$outletBItem->qty.')' : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <label for="recipient">Notes:</label><br>
                    <input type="text" id="recipient" class="form-control" name="note">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary mt-3">Give Out</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

<script>
    window.addEventListener('load', function() {

        var items_table = $('#items-data-table').DataTable({
            lengthChange: false,
        });

        items_table.buttons().container().appendTo('#items-data-table_wrapper .col-md-6:eq(0)');

        $('#form').on('submit', function(e) {
            // Loop over all inputs (even hidden ones)
            items_table.$('input, select, textarea').each(function() {
                if (!$.contains(document, this)) {
                    // Append hidden field with same name and value
                    $('<input>').attr({
                        type: 'hidden',
                        name: this.name,
                        value: $(this).val()
                    }).appendTo('#form');
                }
            });
        });
    });
</script>