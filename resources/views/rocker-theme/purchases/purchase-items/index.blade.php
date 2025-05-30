@extends('dashboard.layouts.app')

<style>
    .user-photo {
        width: 40px;
        height: auto;
    }
</style>

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
                        <li class="breadcrumb-item active" aria-current="page">Purchase Items</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="{{ route('purchase-store-items.create') }}" class="btn btn-dark">Add New</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <!--include flash message manually if you wish -->
                <div class="table-responsive">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Total Amount</th>
                                <th>Discount</th>
                                <th>Rate</th>
                                <th>Unit Quantity</th>
                                <th>Tax Amount</th>
                                <th>Tax Rate</th>
                                <th>Store Item</th>
                                <th>Purchase</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @if ($purchaseStoreItems->count())
                            <tbody>
                                @foreach ($purchaseStoreItems as $purchaseItem)
                                    @php
                                        $purchaseItemStatus = $purchaseItem->status;
                                        $purchaseItemStatusColor = '';
                                        if ($purchaseItemStatus == 'Recieved') {
                                            $purchaseItemStatusColor = 'text-success';
                                        }
                                        if ($purchaseItemStatus == 'Partial') {
                                            $purchaseItemStatusColor = 'text-danger';
                                        }
                                        if ($purchaseItemStatus == 'Ordered') {
                                            $purchaseItemStatusColor = 'text-primary';
                                        }
                                        if ($purchaseItemStatus == 'Pending') {
                                            $purchaseItemStatusColor = 'text-warning';
                                        }
                                    @endphp
                                    <tr>
                                        <td>₦{{number_format( $purchaseItem->amount) }}</td>
                                        <td>₦{{ number_format($purchaseItem->total_amount) ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($purchaseItem->discount) }}</td>
                                        <td>₦{{ number_format($purchaseItem->rate )}}</td>
                                        <td>{{ $purchaseItem->unit_qty }}</td>
                                        <td>₦{{ number_format($purchaseItem->tax_amount) ?? 'N/A' }}</td>
                                        <td>₦{{ number_format($purchaseItem->tax_rate) ?? 'N/A' }}</td>
                                        <td>{{ $purchaseItem->storeItem->name }}</td>
                                        <td>{{ $purchaseItem->purchase->id }}</td>
                                        <td>
                                            <div class="d-flex order-actions">
                                                <a href="{{ route('purchase-store-items.edit', $purchaseItem->id) }}">
                                                    <i class='bx bxs-edit'></i>
                                                </a>
                                                <a href="#" onclick="confirmDelete(event,{{ $purchaseItem->id }});" class="ms-3">
                                                    <i class='bx bxs-trash'></i>
                                                </a>
                                            </div>
                                            <form id="delete-form-{{ $purchaseItem->id }}"
                                                action="{{ route('purchase-store-items.destroy', $purchaseItem->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
        
                                            <script>
                                                function confirmDelete(event, purchaseItem_Id) {
                                                    event.preventDefault();
                                                    if (confirm('Are you sure you want to delete this purchase store item?')) {
                                                        document.getElementById('delete-form-' + purchaseItem_Id).submit();
                                                    }
                                                }
                                            </script>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <tbody>
                                <tr>
                                    <td colspan="11">
                                        <h4>No Available Purchase Item</h4>
                                    </td>
                                </tr>
                            </tbody>
                        @endif
                    </table>

                </div>
            </div>
        </div>
    </div>
    <!--end row-->
    </div>
@endsection
