<table id="suppliers-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Name</th>
            <th>Contact Name</th>
            <th>Phone</th>
            <th>Total Supplies</th>
            <th>Total Payment</th>
            <th>Total Owning</th>
            <th>Action</th>
        </tr>
    </thead>
    @if ($suppliers->count())
        <tbody>

            @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_person_name }}</td>
                    <td>{{ $supplier->contact_person_phone }}</td>
                    <td>{{ $supplier->getTotalExpensesAmount() }}</td>
                    <td>{{ $supplier->getTotalPaymentsAmount() }}</td>
                    <td>{{ $supplier->getBalance() }}</td>

                    <td>

                        <div class="d-flex order-actions">
                            <a href="{{ route('suppliers.edit', $supplier->id) }}">
                                <i class='bx bxs-edit'></i>
                            </a>
                            <a href="#" onclick="confirmDelete(event, {{ $supplier->id }});" class="ms-3">
                                <i class='bx bxs-trash'></i>
                            </a>
                        </div>
                        <form id="delete-form-{{ $supplier->id }}"
                            action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <script>
                            function confirmDelete(event, supplierId) {
                                event.preventDefault();
                                if (confirm('Are you sure you want to delete this supplier?')) {
                                    document.getElementById('delete-form-' + supplierId).submit();
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
                <td colspan="8">
                    <h4>No Available Supplier</h4>
                </td>
            </tr>
        </tbody>
    @endif
</table>
