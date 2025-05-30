<div class="table-responsive">
    <table id="store-items-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Code</th>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>For Sale</th>
                
            </tr>
        </thead>
        @forelse ($storeItems as $store_item)
        <tbody>
            <tr>
                <td>{{ $store_item->code }}</td>
                <td>
                @if($store_item->image)
                    <img style="width: 40px;" class="img-fluid item-image" src="{{ asset('storage/' . $store_item->image) }}" alt=" Image">
                @else
                    <img style="width: 40px;" class="img-fluid user-photo" src="{{ url('dashboard/assets/images/store-item.png') }}" alt="Image">
                @endif
                </td>
                <td>{{ $store_item->name }}</td>
                <td>{{ $store_item->category() }}</td>
                <td>{{ $store_item->qty }}</td>
                <td>{{ $store_item->unit_measurement }}</td>
                <td>{{ $store_item->for_sale ? 'Yes' : 'No' }}</td>
                <td>
                    <div class="d-flex order-actions">
                        <a href="{{ route('store-items.show', $store_item) }}">
                            <i class='bx bxs-show'></i>
                        </a>
                        <a href="#" onclick="confirmDelete(event,{{ $store_item->id }});" class="ms-3">
                            <i class='bx bxs-trash'></i>
                        </a>
                    </div>
                    <form id="delete-form-{{ $store_item->id }}"
                        action="{{ route('store-items.destroy', $store_item->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                    <script>
                        function confirmDelete(event, store_item_Id) {
                            event.preventDefault();
                            if (confirm('Are you sure you want to delete this store item?')) {
                                document.getElementById('delete-form-' + store_item_Id).submit();
                            }
                        }
                    </script>
                </td>
            </tr>
            </tbody>
        @empty
            <h4>No Store Items. <a href="{{ url('store/import-items') }}">Import Now</a></h4>
        @endforelse
    </table>
</div>