<style>
    /* General Material Design Style */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
        margin-top: 10px;
    }

    .file-input {
        display: none;
    }

    .file-input-label {
        background-color: #6200ea;
        color: white;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        border-radius: 4px;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-align: center;
    }

    .file-input-label:hover {
        background-color: #3700b3;
    }

    .file-input-label i {
        margin-right: 5px;
    }

    .file-input-label:active {
        background-color: #03dac6;
    }

    /* Image Preview */
    #editReviewImages {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .image-preview-container {
        position: relative;
        display: inline-block;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .image-preview-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 50%;
        padding: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .delete-btn:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }
</style>

<div class="modal fade" id="editReviewModal" tabindex="-1" role="dialog" aria-labelledby="editReviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReviewModalLabel">Edit Review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for editing the review -->
                <form id="editReviewForm">
                    <input type="hidden" name="review_id" id="editReviewId">
                    <div class="form-group">
                        <label for="editReviewText">Review</label>
                        <textarea class="form-control" id="editReviewText" name="review" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editReviewImages" class="d-block font-weight-bold">Images</label>
                        <div id="editReviewImages" class="d-flex flex-wrap mb-3"></div>

                        <!-- File input styled like Material Design -->
                        <div class="file-input-wrapper">
                            <input type="file" name="new_images[]" id="newImages" multiple class="file-input" />
                            <label for="newImages" class="file-input-label">
                                <i class="fas fa-cloud-upload-alt"></i> Upload Images
                            </label>
                        </div>
                        <small class="form-text text-muted">You can upload new images or keep existing ones.</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveEditReview">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#editReviewModal').on('show.bs.modal', function (e) {
        let reviewId = $(e.relatedTarget).data('id');
        $('#editReviewId').val(reviewId); // Set the hidden input for the review ID

        // Clear previous data
        $('#editReviewText').val('');
        $('#editReviewImages').html('');

        // Fetch the review details
        $.ajax({
            url: 'api/review/fetch-single-review.php',
            method: 'GET',
            data: { id: reviewId },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    let review = response.data;

                    // Populate the review text
                    $('#editReviewText').val(review.review);

                    // Populate the images
                    let imagesHtml = '';
                    review.images.forEach((img) => {
                        imagesHtml += `
                        <div class="position-relative m-2">
                            <img src="../admin/review_image/${img}" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                style="top: 0; right: 0; padding: 0.25rem 0.5rem; font-size: 0.75rem;" data-image="${img}" onclick="removeImage(this, '${reviewId}')">
                                <i class="fas fa-times" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>
                    `;
                    });
                    $('#editReviewImages').html(imagesHtml);
                } else {
                    alert('Failed to fetch review details.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching review:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    errorThrown: error,
                });
                alert('An error occurred while fetching the review.');
            }
        });
    });

    // Remove image function
    function removeImage(button, reviewId) {
        let imageName = $(button).data('image');

        // Remove the image from the UI
        $(button).parent().remove();

        // Send AJAX request to delete the image from the server
        $.ajax({
            url: 'api/review/delete-image.php',
            method: 'POST',
            data: { review_id: reviewId, image_name: imageName },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    alert('Image removed successfully.');
                } else {
                    alert('Failed to remove the image.');
                }
            }
        });
    }
</script>

<script>
    $('#saveEditReview').on('click', function (e) {
        e.preventDefault();

        let formData = new FormData($('#editReviewForm')[0]);

        $.ajax({
            url: 'api/review/update-review.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("Raw response:", response); // Debug log for the raw response
                response = JSON.parse(response);
                if (response.success) {
                    $('#editReviewModal').modal('hide');
                    alert('Review updated successfully!');
                    location.reload();
                } else {
                    alert(response.message || 'Failed to update the review.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching review:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    errorThrown: error,
                });
                alert('An error occurred while updating the review.');
            }
        });
    });

</script>