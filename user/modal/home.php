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
                    $options = '<option value="" disabled selected>Select a location</option>'; // Add blank option
                    
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

    .image-wrapper {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }
</style>


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