<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteBarOrderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" id="customer-form" action="{{ url('customers/') }}">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('load', function() {
        $(document).on('click', '.delete-customer', function(event) {
            event.preventDefault(); // Prevent default anchor click behavior
            var customerId = $(this).data('customer-id'); // Use the correct data attribute name
            var baseUrl = "{{ url('customers') }}";
            var newUrl = baseUrl + "/" + customerId;

            // Update the form action attribute with the new URL
            $("#customer-form").attr("action", newUrl);
        });

    });
</script>