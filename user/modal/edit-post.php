<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-edit-post modal-dialog-centered" role="document">
        <div class="modal-content">
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
                            <h6 style="font-size:15px;"><span class="location-selected"></span></h6>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-left:37px; margin-top:-10px;">
                    <div class="col">

                        <input type="file" id="selected-images" name="images[]" multiple style="display: none;">
                        <input type="hidden" id="existing-image-path" name="existing-image-path">
                        <input type="hidden" id="image-path" name="image-path">
                        <input type="hidden" id="editLocation" class="form-control" name="editLocation"
                            placeholder="Select Location">
                        <input type="hidden" id="editPostId" name="post_id">

                        <button class="btn btn-default btn-custom" type="button" id="add-photo-btn">
                            <i class="far fa-images" style="font-size:12px;"></i>&nbsp; Add photo
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
            </div>
        </div>
    </div>
</div>

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

    .image-wrapper {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }
</style>

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
                    <?php
                    include '../inc/config.php';
                    $pdo_statement = $pdo->prepare("SELECT id, location_name FROM tbl_location");
                    $pdo_statement->execute();
                    $locations = $pdo_statement->fetchAll(PDO::FETCH_ASSOC);
                    $location_options = '<option value="" disabled selected>Select a location</option>'; // Add blank option
                    
                    foreach ($locations as $location) {
                        $location_options .= '<option value="' . htmlspecialchars($location['location_name']) . '">' . htmlspecialchars($location['location_name']) . '</option>';
                    }
                    ?>

                    <select id="editLocationSelect" class="form-control" name="location">
                        <?php echo $location_options; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="select-location-btn">Add Location</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var addLocationBtn = document.getElementById('select-location-btn');
        var locationSelect = document.getElementById('editLocationSelect');
        var selectedLocationInput = document.getElementById('editLocation');
        var selectedLocationDisplay = document.querySelector('.location-selected');

        // Event listener for adding selected location
        addLocationBtn.addEventListener('click', function () {
            var selectedLocation = locationSelect.value; // Get the selected location
            if (selectedLocation) {
                selectedLocationInput.value = selectedLocation; // Update the input field
                selectedLocationDisplay.textContent = selectedLocation; // Update the display using textContent
            }
            $('#select-location').modal('hide'); // Close the modal
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('#save-post-btn').on('click', function () {
            var editPostId = $('#editPostId').val();
            var editPostText = $('#editPostText').val();
            var editLocation = $('#editLocation').val();
            var selectedImages = $('#selected-images')[0].files;
            var deletedImages = $('#deleted-images').val(); // Get the deleted images

            var formData = new FormData();
            formData.append('post_id', editPostId);
            formData.append('post', editPostText);
            formData.append('editLocation', editLocation);
            formData.append('deletedImages', deletedImages); // Send deleted images

            for (var i = 0; i < selectedImages.length; i++) {
                formData.append('images[]', selectedImages[i]);
            }

            $.ajax({
                url: 'api/home/update-post.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert('Post updated successfully!');
                    } else {
                        alert('Failed to update post. Please try again.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while updating the post.');
                }
            });
        });
    });

</script>