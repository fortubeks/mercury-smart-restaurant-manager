@extends('rocker-theme.layouts.app')
@section('content')
<h5 class="mb-3">Migrate Items</h5>


<div class="row mb-5">
    <div class="col-xl-12 mx-auto">
        <form action="{{ route('store-item.migrate-items.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="hidden" name="from_id" value="{{ $storeA->id }}">
                <input type="hidden" name="to_id" value="{{ $storeB->id }}">
                <input type="hidden" name="from_type" value="store">
                <input type="hidden" name="to_type" value="store">
            </div>

            <table class="table table-bordered mb-0" id="migration-table">
                <thead>
                    <tr>
                        <th>Item Code ({{ $storeA->name }})</th>
                        <th>{{ $storeA->name }}</th>
                        <th>Quantity to Send</th>
                        <th>{{ $storeB->name }}</th>
                        <th>Item Code ({{ $storeB->name }})</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storeAItems as $storeAItem)
                    @php
                    $storeItemName = $storeAItem->name;
                    $storeBItem = $storeBItems->get($storeItemName);
                    $storeAQty = $storeAItem->pivot->qty ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $storeAItem->code }}</td>
                        <td>{{ $storeAItem->name . ' (' . $storeAQty . ')' }}</td>
                        <td>
                            <input type="number"
                                class="form-control"
                                name="quantities[{{ $storeAItem->pivot->id }}]"
                                min="1"
                                max="{{ $storeAQty }}">
                        </td>
                        <td>{{ $storeBItem ? $storeBItem->name . ' (' . ($storeBItem->pivot->qty ?? 0) . ')' : 'N/A' }}</td>
                        <td>{{ $storeBItem ? $storeBItem->code : 'N/A' }}</td>
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
        $('#migration-table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [10, 25, 50, 100],
            columnDefs: [{
                    orderable: false,
                    targets: 2
                } // disable sorting on "Quantity to Send"
            ]
        });
    });
</script>