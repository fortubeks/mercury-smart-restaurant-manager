<table id="purchases-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Supplier</th>
            <th>Payment Status</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    @if ($purchases->count())
        <tbody>
            @foreach ($purchases as $purchase)
                @php
                    $purchaseStatus = $purchase->status;
                    $purchaseStatusColor = '';
                    if ($purchaseStatus == 'Received') {
                        $purchaseStatusColor = 'text-success';
                    }
                    if ($purchaseStatus == 'Partial') {
                        $purchaseStatusColor = 'text-danger';
                    }
                    if ($purchaseStatus == 'Ordered') {
                        $purchaseStatusColor = 'text-primary';
                    }
                    if ($purchaseStatus == 'Pending') {
                        $purchaseStatusColor = 'text-warning';
                    }
                @endphp
                <tr>
                  
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $purchase->purchase_date)->format('jS, M Y') }}</td>
                    <td class="">{{ $purchase->category->name ?? '' }}</td>
                    <td class="">{{ $purchase->supplier->name ?? '' }}</td>
                    <td class="{{ $purchaseStatusColor }}">{{ $purchase->status }}</td>
                    <td class="text-right">₦{{ number_format($purchase->amount) ?? '' }}</td>
                    <td>
                        <div class="d-flex order-actions">
                            <a href="{{ route('purchases.edit', $purchase->id) }}">
                                <i class='bx bxs-edit'></i>
                            </a>

                            <a href="" class=""> <i class='bx bx-show'></i></a>
                            <a href="#" onclick="setId(this)"
                                data-amount="{{ $purchase->amount }}" data-id="{{ $purchase->id }}"
                                class="" data-toggle="modal"
                                data-target="#modal-payment"><i class="fadeIn animated bx bx-money"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr>
                <td colspan="9">
                    <h4>No Available Purchase</h4>
                </td>
            </tr>
        </tbody>
    @endif
</table>