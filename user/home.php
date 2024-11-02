<?php
session_start();
include 'header.php';
include 'modal/home.php';
include 'modal/report.php';
include 'modal/edit-post.php';
?>

<head>
    <link rel="stylesheet" href="assets/css/home.css">
</head>

<div class="content-wrapper">


    <section class="content mt-2">


        <?php
        include 'components/home/create-new-post.php';
        include 'components/home/companion-post.php';
        ?>

        <?php
        if (!isset($_SESSION['user'])) {
            include '../inc/config.php';
            $stmt = $pdo->prepare("SELECT * FROM tbl_review tr LEFT JOIN tbl_location tl ON tl.id = tr.location_id LEFT JOIN tbl_user tu ON tu.id = tr.user_id ORDER BY tr.id DESC");
            $stmt->execute();
            $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>



            <div class="row">
                <div class="col">

                    <h6 class="font-weight-bold ml-2">Latest</h6>
                    <?php
                    // include 'fetch_locations.php';
                    if (!empty($locations)) {
                        foreach ($locations as $location) {
                            $images = $location['images'];
                            $imageArray = explode(',', $images);
                            $firstImage = isset($imageArray[0]) ? trim($imageArray[0]) : null;
                            if ($location['profile_img'] == "") {
                                $profile_img = '../dist/img/avatar2.png';
                            } else {
                                $profile_img = '../admin/profile_image/' . $location['profile_img'];
                            }
                            echo '
                        <div class="col-md-4 mb-3">
                            <div class="card elevation-2" style="background-color:#6CB4EE;">
                                <div class="card-body">
                                <div class="row">
                                <div class="col-auto">
                                <img src="' . $profile_img . '" class="img-circle elevation-2"  style="width:30px; height:30px; margin-top:-4px;">
                                </div>
                                <div class="col-auto" style="margin-left:-10px;">
                                <h6 class="font-weight-bold" style="font-size:14px;">' . $location['name'] . '</h6>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-8 m-auto">
                                   <p class="card-text" style="font-size:13px; line-height: 1.2;">' . htmlspecialchars($location['review']) . '</p>
                                </div>
                                    <div class="col-4 mx-auto">
                                   <p class="card-text"><img src="../admin/review_image/' . $firstImage . '" style="width:70px; height:60px;"></p>
                                </div>
                                </div>
                                    
                                    
                                </div>
                            </div>
                        </div>';
                        }
                    } else {
                        echo '<p>No locations found.</p>';
                    }
                    ?>
                </div>
            </div>


            <?php
        }
        ?>



        <?php
        if (!isset($_SESSION['user'])) {
            include '../inc/config.php';
            $stmt = $pdo->prepare("SELECT * FROM tbl_announcement ORDER BY id DESC LIMIT 2");
            $stmt->execute();
            $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="row">
                <div class="col">
                    <h6 class="font-weight-bold ml-2">News and Updates</h6>
                    <?php
                    // include 'fetch_locations.php';
                    if (!empty($locations)) {
                        foreach ($locations as $location) {
                            echo '
                        <div class="col-md-4 mb-3">
                            <div class="card elevation-2" style="background-color:#6CB4EE;">
                                <div class="card-body">
                                <div class="row">
                                <div class="col-auto">
                                <img src="../img/logo.png" class="img-circle" style="margin-top:-12px; width:20px;">
                                </div>
                                <div class="col-auto" style="margin-left:-10px;">
                                <h6 class="font-weight-bold" style="font-size:14px;">TripSociety</h6>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-8 m-auto">
                                   <p class="card-text" style="font-size:13px; line-height: 1.2;">' . htmlspecialchars($location['description']) . '</p>
                                </div>
                                    <div class="col-4 mx-auto">
                                   <p class="card-text"><img src="../admin/announcement/' . $location['image'] . '" style="width:70px; height:60px;"></p>
                                </div>
                                </div>
                                    
                                    
                                </div>
                            </div>
                        </div>';
                        }
                    } else {
                        echo '<p>No locations found.</p>';
                    }
                    ?>
                </div>
            </div>


            <?php
        }
        ?>



</div>

<br><br>
<br><br>

</div>




</div>







<style>
    /* Ensure images within Summernote are aligned and sized consistently */
    .note-editor .note-editable img {
        max-width: 48%;
        /* Adjust to fit two images per row */
        height: auto;
        display: inline-block;
        /* Align images in a row */
        margin: 0 1% 10px;
        /* Add spacing between images and margin below */
    }

    /* Container for images to ensure they are aligned properly */
    .note-editor .note-editable {
        display: flex;
        flex-wrap: wrap;
        /* Allow images to wrap onto the next line */
    }
</style>


<?php
include 'footer.php';
?>

<script>
    $(document).ready(function () {
        // Event handler for the "Report Post" link
        $('a[data-toggle="modal"]').on('click', function () {
            var postId = $(this).data('id'); // Get post ID from data-id attribute
            $('#postIdInput').val(postId); // Set the value of the input field to the post ID
        });
    });
</script>


<script>
    $(document).ready(function () {
        $(document).on('click', '.reactionButton', function () {
            var button = $(this);
            var icon = button.find('i');
            var postId = button.data('id'); // Fetch the ID from data-id attribute

            $.ajax({
                url: '../inc/function.php?add_reaction', // PHP script to handle the reaction
                type: 'POST',
                data: {
                    action: 'toggle_reaction',
                    post_id: postId // Send the post ID to the server
                },
                success: function (response) {
                    if (response.reacted) {
                        // Change icon to filled heart
                        icon.removeClass('far fa-heart').addClass('fas fa-heart');
                    } else {
                        // Change icon to empty heart
                        icon.removeClass('fas fa-heart').addClass('far fa-heart');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });

</script>





<script>
    document.addEventListener('DOMContentLoaded', function () {
        var textarea = document.getElementById('messageTextarea');
        var publishButton = document.getElementById('publishButton');

        textarea.addEventListener('input', function () {
            if (textarea.value.trim().length > 0) {
                publishButton.disabled = false; // Enable button
            } else {
                publishButton.disabled = true; // Disable button
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        const maxImages = 4;

        // Handle button click to trigger file input
        $('#addPhotoButton').click(function () {
            $('#imageInput').click();
        });

        // Limit file input to maxImages
        $('#imageInput').change(function () {
            const files = Array.from(this.files).slice(0, maxImages); // Only consider up to 4 images
            const previewPostContainer = $('#imagePostPreviewContainer');
            previewPostContainer.empty(); // Clear the preview container

            // Process each selected image file up to the max limit
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Create a wrapper div for each image with delete button
                    const imgWrapper = $('<div>').css({
                        'position': 'relative',
                        'display': 'inline-block',
                        'margin': '5px'
                    });

                    // Create the image element
                    const img = $('<img>').attr('src', e.target.result).css({
                        'width': '200px',
                        'height': '200px',
                        'object-fit': 'cover'
                    });

                    // Create the delete button
                    const deleteButton = $('<span>').text('×').css({
                        'position': 'absolute',
                        'top': '5px',
                        'right': '5px',
                        'height': '20px',
                        'width': '20px',
                        'background': 'rgba(0,0,0,0.5)',
                        'color': 'white',
                        'border-radius': '50%', // Circle shape
                        'display': 'flex',       // Center content with flexbox
                        'align-items': 'center', // Vertical center
                        'justify-content': 'center', // Horizontal center
                        'cursor': 'pointer',
                        'font-size': '14px',     // Adjusted size for centering
                        'font-weight': 'bold'
                    }).click(function () {
                        imgWrapper.remove(); // Remove the image wrapper on delete button click
                        updateFileNames(); // Update hidden input field with current file names
                    });


                    // Append image and delete button to wrapper, then to preview container
                    imgWrapper.append(img).append(deleteButton);
                    previewPostContainer.append(imgWrapper);
                };
                reader.readAsDataURL(file);
            });

            // Update hidden input with current file names
            const updateFileNames = () => {
                const fileNames = Array.from(previewPostContainer.find('img')).map(img => img.src.split('/').pop());
                $('#imagePaths').val(fileNames.join(','));
            };

            updateFileNames();
        });
    });





    document.addEventListener('DOMContentLoaded', function () {
        var textarea = document.querySelector('.postDetail');
        var bottomSheet = document.getElementById('bottomSheet');
        var closeSheetButton = document.getElementById('closeSheet');
        var addLocationButton = document.getElementById('addLocation');
        var locationSelect = document.getElementById('locationSelect');
        var selectedLocationDisplay = document.getElementById('selectedLocation');

        $('.postDetail').on('focus', function () {
            $('#bottomSheet').addClass('show');
        });

        // Close button functionality
        $('#closeSheet').on('click', function () {
            $('#bottomSheet').removeClass('show');
        });

        // Add location button functionality
        $('#addLocation').on('click', function () {
            const selectedLocation = $('#locationSelect').val();
            if (selectedLocation) {
                $('#selectedLocation').text(selectedLocation);
                $('#location-selected').val(selectedLocation);
            }
            $('#pinMapModal').modal('hide');
        });


    });
</script>