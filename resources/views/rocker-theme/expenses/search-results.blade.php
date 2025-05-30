<table id="expenses-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#ID</th>
            <th>Date</th>
            <th>Category</th>
            <th>Description</th>
            <th>Supplier</th>
            <th>Payment Status</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    @if ($expenses->count())
        <tbody>
            @foreach ($expenses as $expense)
                @php
                    $expenseStatus = $expense->status;
                    $expenseStatusColor = '';
                    if ($expenseStatus == 'Recieved') {
                        $expenseStatusColor = 'text-success';
                    }
                    if ($expenseStatus == 'Partial') {
                        $expenseStatusColor = 'text-danger';
                    }
                    if ($expenseStatus == 'Ordered') {
                        $expenseStatusColor = 'text-primary';
                    }
                    if ($expenseStatus == 'Pending') {
                        $expenseStatusColor = 'text-warning';
                    }
                @endphp
                <tr>
                    <td>{{ $expense->id }}</td>
                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d', $expense->expense_date)->format('jS, M Y') }}</td>
                    <td>{{ $expense->category->name }}</td>
                    <td class="">{{ $expense->getItems() ?? '' }}</td>
                    <td>{{ $expense->supplier->name ?? 'N/A' }}</td>
                    <td class="">{{ $expense->paymentStatus() ?? '' }}</td>
                    <td class="text-right">{{ formatCurrency($expense->amount) ?? '' }}</td>
                    <td>
                        <div class="d-flex order-actions">
                            <a href="{{ route('expenses.edit', $expense->id) }}">
                                <i class='bx bxs-edit'></i>
                            </a>
                            <a href="#" onclick="openDeleteModal('{{ $expense->id }}');" class="ms-3">
                                <i class='bx bxs-trash'></i>
                            </a>
                        </div>
                        <form id="delete-form-{{ $expense->id }}" 
                            action="{{ route('expenses.destroy', $expense->id) }}" method="POST"  
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                    
                    
                </tr>
            @endforeach
        </tbody>
    @else
        <tbody>
            <tr>
                <td colspan="8">
                    <h4>No Available Expenses</h4>
                </td>
            </tr>
        </tbody>
    @endif
</table>