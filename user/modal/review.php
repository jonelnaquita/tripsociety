<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <div class="modal-body text-center">
                <p class="mb-4">Are you sure you want to delete this review?</p>
                <div>
                    <button type="button" class="btn btn-secondary cancel-btn rounded-pill mr-2"
                        data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger rounded-pill"
                        id="confirmDeleteReviewBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .modal-content {
        border-radius: 15px;
        /* Rounded corners for the modal */
    }

    .modal-body {
        padding: 2rem;
        /* Add more padding to the modal body for spacing */
    }

    .footer-button .btn {
        padding: 10px 20px;
        /* Increase padding for a better button size */
        font-size: 16px;
        /* Adjust font size */
    }

    .cancel-btn {
        background-color: #f0f0f0;
        /* Light gray background for the cancel button */
        border: none;
        color: #000;
    }

    .delete-btn-2 {
        background-color: #e57373;
        /* Material red color */
        border: none;
        /* Remove border */
    }
</style>