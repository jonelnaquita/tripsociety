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
                        <input type="hidden" id="deleted-images" name="deleted-images">
                        <!-- Hidden input for deleted images -->
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
                        toastr.success('Post updated successfully!');
                        setTimeout(function () {
                            location.reload(); // Reload the page after 2 seconds
                        }, 1000); // 2000 milliseconds = 2 seconds
                    } else {
                        toastr.error('Failed to update post. Please try again.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('An error occurred while updating the post.');
                }
            });
        });
    });

    $(document).on('click', '#editPostBtn', function () {
        var postId = $(this).data('id');

        $.ajax({
            url: 'api/home/fetch-post.php',
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                console.log("Fetched Post Data:", response);
                $('#editPostId').val(response.id);
                $('#editPostText').val(response.post);
                $('#editLocation').val(response.location);
                $('.location-selected').text(response.location);

                // Clear previous image previews
                $('#imagePreviewContainer').html('');
                $('#deleted-images').val(''); // Reset deleted images input

                // Check if images exist before displaying them
                if (response.images && response.images.length > 0) {
                    console.log("Image Paths:", response.images);
                    response.images.forEach(function (img) {
                        addImagePreview(img, true); // Pass true to indicate it's an existing image
                    });
                    // Store existing images as filenames in a hidden input
                    const existingFilenames = response.images.map(img => img.split('/').pop()).join(',');
                    $('#existing-image-path').val(existingFilenames);
                } else {
                    console.log("No images found for this post.");
                    // If there are no images, ensure that the existing image path is cleared
                    $('#existing-image-path').val('');
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });

    $(document).ready(function () {
        const maxImages = 4;

        $('#add-photo-btn').click(function () {
            $('#selected-images').click();
        });

        $('#selected-images').change(function () {
            const files = this.files;

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
                    addImagePreview(e.target.result); // Add the new image to the preview
                };
                reader.readAsDataURL(file);
            });

            // Update the hidden input field with file names, including existing images
            updateFileNames();
        });
    });

    // Add Image Preview Function
    function addImagePreview(src, isExisting = false) {
        const imgWrapper = $('<div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 10px;">');
        const img = $('<img>').attr('src', src).css({
            'width': '200px',
            'height': '200px',
            'object-fit': 'cover',
            'border-radius': '5px'
        });

        const deleteButton = $('<span>').text('×').css({
            'position': 'absolute',
            'top': '5px',
            'right': '5px',
            'height': '20px',
            'width': '20px',
            'background': 'rgba(0,0,0,0.5)',
            'color': 'white',
            'border-radius': '50%',
            'display': 'flex',
            'align-items': 'center',
            'justify-content': 'center',
            'cursor': 'pointer',
            'font-size': '14px',
            'font-weight': 'bold'
        }).click(function () {
            imgWrapper.remove(); // Remove the image wrapper on delete button click
            updateFileNames(src, isExisting); // Update hidden input field with current file names
            // Track deleted images
            if (isExisting) {
                trackDeletedImage(src); // Call function to track deleted image
            }
        });

        imgWrapper.append(img).append(deleteButton);
        $('#imagePreviewContainer').append(imgWrapper);
        updateFileNames(); // Update the file names after adding a new image
    }

    // Function to track deleted images
    function trackDeletedImage(src) {
        const deletedImages = $('#deleted-images').val();
        const currentDeletedImages = deletedImages ? deletedImages.split(',') : [];

        // Extract filename only from the image source
        const filenameToDelete = src.split('/').pop(); // Get the filename from path

        if (!currentDeletedImages.includes(filenameToDelete)) {
            currentDeletedImages.push(filenameToDelete);
            $('#deleted-images').val(currentDeletedImages.join(',')); // Update hidden input for deleted images
        }
    }

    // Update hidden input with current file names
    const updateFileNames = (deletedImagePath = '', isExisting = false) => {
        const existingImages = $('#existing-image-path').val().split(',').filter(Boolean); // Get existing images
        const newImages = Array.from($('#imagePreviewContainer').find('img')).map(img => img.src);

        // If an existing image is deleted, remove it from the existingImages array
        if (isExisting && deletedImagePath) {
            // Extract only the filename from the deletedImagePath
            const filenameToDelete = deletedImagePath.split('/').pop(); // Get the filename from path
            const index = existingImages.indexOf(filenameToDelete);
            if (index > -1) {
                existingImages.splice(index, 1); // Remove the filename
            }
        }

        const allImages = existingImages.concat(newImages);

        // Use only the filenames in the hidden input
        const allFilenames = allImages.map(image => image.split('/').pop()).join(','); // Extract filenames only
        $('#image-path').val(allFilenames); // Update the hidden input field with filenames
    };
</script>