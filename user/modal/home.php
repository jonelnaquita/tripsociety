<!-- Image Preview-->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 bg-transparent">
            <div class="position-relative">
                <img src="" id="modalImage" class="img-fluid" alt="Large Image">
                <button type="button" class="close position-absolute" style="top: 10px; right: 10px;"
                    data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        // When an image is clicked, set the src of the modal image
        $('[data-toggle="modal"]').click(function () {
            var imageSrc = $(this).data('src');
            $('#modalImage').attr('src', imageSrc);
        });
    });
</script>

<!-- Report Post Modal -->
<div class="modal fade" id="reportPostModal" tabindex="-1" role="dialog" aria-labelledby="reportPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportPostModalLabel">Report This Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reportPostForm">
                    <p>Please select the reason for reporting this post:</p>
                    <div class="form-group">
                        <select id="violationSelect" class="form-control" required>
                            <option value="">Select a violation</option>
                            <option value="Hate speech or discriminatory remarks">Hate speech or discriminatory remarks
                            </option>
                            <option value="Harassment or bullying">Harassment or bullying</option>
                            <option value="Spam or irrelevant content">Spam or irrelevant content</option>
                            <option value="Posting false or misleading information">Posting false or misleading
                                information</option>
                            <option value="Illegal activities or content">Illegal activities or content</option>
                            <option value="Impersonation or misrepresentation">Impersonation or misrepresentation
                            </option>
                            <option value="Inappropriate or explicit content">Inappropriate or explicit content</option>
                            <option value="Violations of privacy">Violations of privacy</option>
                        </select>
                    </div>
                    <input type="hidden" name="post_id" id="postIdInput" class="form-control" readonly>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']; ?>" class="form-control">
                    <!-- Hidden input for user ID -->
            </div>
            <div class="modal-footer">
                <button type="button" id="submitReport" class="btn btn-primary btn-sm">Report Post</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!--Select Location-->
<div class="modal fade" id="pinMapModal" tabindex="-1" role="dialog" aria-labelledby="pinMapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinMapModalLabel">Select Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Location:</label>
                    <?php
                    include '../inc/config.php';
                    $pdo_statement = $pdo->prepare("SELECT id, location_name FROM tbl_location");
                    $pdo_statement->execute();
                    $locations = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
                    $options = '';
                    foreach ($locations as $location) {
                        $options .= '<option value="' . htmlspecialchars($location['location_name']) . '">' . htmlspecialchars($location['location_name']) . '</option>';
                    }
                    ?>

                    <!-- HTML part -->
                    <select id="locationSelect" class="form-control" name="location">
                        <?php echo $options; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="addLocation">Add Location</button>
            </div>
        </div>
    </div>
</div>

<!-- Select Location Modal -->
<div class="modal fade" id="select-location" tabindex="-1" role="dialog" aria-labelledby="pinMapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pinMapModalLabel">Select Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Location:</label>
                    <select id="locationSelect" class="form-control" name="location">
                        <?php echo $options; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="select-location-btn">Add Location</button>
            </div>
        </div>
    </div>
</div>




<!-- Delete Post -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this post?</p>
                <div class="action-buttons mt-4">
                    <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #select-location {
        z-index: 1060 !important;
        /* Set a higher z-index for select-location modal */
    }
</style>

<script>
    // Function to open the Select Location modal and keep the editPostModal open
    function openLocationModal() {
        // Show the select-location modal
        $('#select-location').modal({
            backdrop: false,  // Disable backdrop for select-location modal
            show: true
        });
    }

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var addLocationBtn = document.getElementById('select-location-btn');
        var locationSelect = document.getElementById('locationSelect');
        var selectedLocationInput = document.getElementById('editLocation');
        var selectedLocationDisplay = document.querySelector('.location-selected');

        // Event listener for adding selected location
        addLocationBtn.addEventListener('click', function () {
            var selectedLocation = locationSelect.value; // Get the selected location
            if (selectedLocation) {
                selectedLocationInput.value = selectedLocation; // Update the input field
                selectedLocationDisplay.textContent = selectedLocation; // Update the display
            }
            $('#select-location').modal('hide'); // Close the modal
        });
    });
</script>


<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-edit-post modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="editPostForm" method="POST" enctype="multipart/form-data">
                <div class="modal-header d-flex align-items-center" style="padding-left: 0; padding-right: 0;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="margin-left: 0; padding-left: 15px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h6 class="modal-title mx-auto" id="commentModalLabel">Edit Post</h6>
                    <div class="col-4 text-right">
                        <button type="submit" name="edit-post" class="btn btn-default btn-custom" id="save-post-btn">
                            <i class="fas fa-calendar-check"></i> Save Post
                        </button>
                    </div>
                </div>
                <div class="modal-body">

                    <div class="row mt-2">
                        <div class="col-auto m-auto text-center">
                            <?php
                            if ($_SESSION['profile_img'] == "") {
                                echo '<img src="../dist/img/avatar2.png" class="img-circle elevation-2" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">';
                            } else {
                                echo '<img src="../admin/profile_image/' . $_SESSION['profile_img'] . '" class="img-circle elevation-2" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">';
                            }
                            ?>
                        </div>

                        <div class="col mt-3">
                            <p class="font-weight-bold"><?php echo $_SESSION['name']; ?></p>
                            <div style=" float:right; margin-top:-34px;">
                                <h6 style="font-size:15px;"><span class=" location-selected"></span></h6>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-left:37px; margin-top:-10px;">
                        <div class="col">

                            <input type="file" id="selected-images" name="images[]" multiple style="display: none;">
                            <input type="hidden" id="image-path" name="image-path">
                            <input type="hidden" id="editLocation" class="form-control" name="editLocation"
                                placeholder="Select Location">
                            <input type="hidden" id="editPostId" name="post_id">

                            <button class="btn btn-default btn-custom" type="button" id="add-photo-btn"">
                                <i class=" far fa-images" style="font-size:12px;"></i>&nbsp; Add photo
                            </button>

                            <button class="btn btn-default btn-custom ml-2" type="button" onclick="openLocationModal();"
                                style="font-size:12px;">
                                <i class="fas fa-map-pin" style="font-size:12px;"></i>&nbsp; Edit location
                            </button>

                        </div>
                    </div>

                    <div class="row ml-2 mr-2">
                        <div class="col">
                            <textarea class="form-control border-0" id="editPostText" placeholder="What's on your mind?"
                                rows="1" style="resize: none;"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';"></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="image-preview" id="imagePreviewContainer">
                                <!-- Image preview elements will be appended here -->
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>

<style>
    .image-preview img {
        width: 200px;
        /* Set width */
        height: 200px;
        /* Set height for square format */
        object-fit: cover;
        /* Maintain aspect ratio */
        margin-right: 10px;
        /* Space between images */
        border-radius: 5px;
        /* Optional: rounded corners */
    }
</style>

<script>
    $(document).ready(function () {
        const maxImages = 4;

        // Handle button click to trigger file input
        $('#add-photo-btn').click(function () {
            $('#selected-images').click();
        });

        // Handle file input change event
        $('#selected-images').change(function () {
            const files = this.files;
            const previewContainer = $('#imagePreviewContainer');
            previewContainer.empty(); // Clear the preview container

            // Check if the number of files exceeds the maximum limit
            if (files.length > maxImages) {
                alert(`You can only upload a maximum of ${maxImages} images.`);
                $('#selected-images').val(''); // Clear file input
                return;
            }

            // Process each selected file
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Create an image element and append to preview container
                    const img = $('<img>').attr('src', e.target.result).css({
                        'width': '200px',      // Set width to 200px
                        'height': '200px',     // Set height to 200px for square format
                        'object-fit': 'cover',  // Maintain aspect ratio
                        'margin': '5px'
                    });
                    previewContainer.append(img);
                };
                reader.readAsDataURL(file);
            });

            // Create a comma-separated list of file names and set it in the hidden input field
            const filePaths = Array.from(files).map(file => file.name).join(',');
            $('#image-path').val(filePaths);
        });
    });

</script>

<!--Update Post-->
<script>
    $(document).ready(function () {
        $('#editPostForm').on('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Get values from form fields
            var postId = $('#editPostId').val();
            var postText = $('#editPostText').val();
            var location = $('#editLocation').val();
            var images = $('#selected-images')[0].files; // Get the file input

            // Create a FormData object
            var formData = new FormData();
            formData.append('post_id', postId);
            formData.append('post', postText);
            formData.append('editLocation', location);

            // Append images to FormData if there are any
            if (images.length > 0) {
                for (var i = 0; i < images.length; i++) {
                    formData.append('images[]', images[i]);
                }
            }

            // Send AJAX request
            $.ajax({
                url: "api/home/update-post.php", // Update the URL to your PHP file
                type: "POST",
                data: formData,
                contentType: false, // Important for FormData
                processData: false, // Important for FormData
                success: function (response) {
                    alert("Post updated successfully!");
                    // Optionally, you can close the modal or refresh the post section here
                    // Example: location.reload();
                },
                error: function () {
                    alert("Error updating post. Please try again.");
                }
            });
        });
    });
</script>


<!--Comment Section-->
<div class="modal fade" id="addCommentModal" tabindex="-1" role="dialog" aria-labelledby="pinMapModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div id="post_details">

                </div>
                <div id="comment_details">

                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>