<div class="modal fade" id="deleteResourceModal" tabindex="-1" aria-labelledby="deleteRestaurantOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCartModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" id="resource-delete-form" class="resource-delete-form" action="">
                    @csrf @method('delete')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {

        $(".delete-resource").click(function(event) {
            var resourceId = $(this).data('resource-id');
            var baseUrl = $(this).data('resource-url');

            // Construct the new URL with appended restaurant order ID
            var newUrl = baseUrl + "/" + resourceId;

            // Update the form action attribute with the new URL
            $("#resource-delete-form").attr("action", newUrl);
        });
    });
</script>