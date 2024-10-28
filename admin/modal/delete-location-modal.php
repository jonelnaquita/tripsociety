<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header font-weight-normal">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this entry?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirm-delete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>


<script>
    let locationId; // Declare a variable to hold the location ID

    // Handle the click event for the delete button
    $(document).on('click', '.delete-location-btn', function () {
        // Get the data-id from the clicked button
        locationId = $(this).data('id'); // Store the ID in the variable

        // Show the confirmation modal
        $('#confirmDeleteModal').modal('show');
    });

    // Handle the confirmation of deletion
    $('#confirm-delete').click(function () {
        // AJAX request to delete the entry
        $.ajax({
            url: 'api/location/delete-location.php', // Update with the correct path to your PHP file
            type: 'POST',
            data: { id: locationId }, // Use the stored locationId
            success: function (response) {
                const result = JSON.parse(response);
                if (result.success) {
                    // Optionally, you might want to remove the corresponding row from the UI
                    // You can also refresh the page or show a success message
                    $('#confirmDeleteModal').modal('hide');
                    toastr.success('Entry deleted successfully!');
                    // Optionally refresh the data or remove the deleted row from the UI
                } else {
                    alert('Error: ' + result.error);
                }
            },
            error: function () {
                alert('An error occurred while trying to delete the entry.');
            }
        });
    });
</script>