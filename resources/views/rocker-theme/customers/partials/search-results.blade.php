<table id="customers-table" class="table">
    @if ($customers->count())
    <tbody>
        @foreach ($customers as $customer)
        <tr>
            <td>
                {{ $customer->name() }}
                <span class="text-secondary">{{$customer->email}}</span>
            </td>
            <td>{{ $customer->phone }}</td>
            <td>{{Str::limit( $customer->address, 20)}}</td>
            <td>
                <div class="d-flex align-items-center order-actions">
                    <a href="{{ route('customers.show', $customer->id) }}" class="me-3">
                        <i class='bx bx-show'></i>
                    </a>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="me-3">
                        <i class='bx bx-pen'></i>
                    </a>
                    <a class="ms-3 delete-customer" href="javascript:void(0);" data-customer-id="{{$customer->id}}" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal"><i class="bx bxs-trash"></i></a>
                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
    @else
    <tbody>
        <tr>
            <td colspan="7">
                <h6>No Result</h6>
            </td>
        </tr>
    </tbody>
    @endif
</table>