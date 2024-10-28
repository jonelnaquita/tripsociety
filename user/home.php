<?php
include 'header.php';
include 'modal/home.php';
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
                                <img src="' . $profile_img . '" class="img-circle" style="margin-top:-12px; width:20px;">
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

        // Handle file input change event
        $('#imageInput').change(function () {
            const files = this.files;
            const previewPostContainer = $('#imagePostPreviewContainer');
            previewPostContainer.empty(); // Clear the preview container

            // Check if the number of files exceeds the maximum limit
            if (files.length > maxImages) {
                alert(`You can only upload a maximum of ${maxImages} images.`);
                $('#imageInput').val(''); // Clear file input
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
                    previewPostContainer.append(img);
                };
                reader.readAsDataURL(file);
            });

            // Create a comma-separated list of file names and set it in the hidden input field
            const filePaths = Array.from(files).map(file => file.name).join(',');
            $('#imagePaths').val(filePaths);
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var textarea = document.querySelector('.postDetail');
        var bottomSheet = document.getElementById('bottomSheet');
        var closeSheetButton = document.getElementById('closeSheet');
        var addLocationButton = document.getElementById('addLocation');
        var locationSelect = document.getElementById('locationSelect');
        var selectedLocationDisplay = document.getElementById('selectedLocation');

        textarea.addEventListener('focus', function () {
            bottomSheet.classList.add('show');
        });

        closeSheetButton.addEventListener('click', function () {
            bottomSheet.classList.remove('show');
        });

        // Event listener for adding selected location
        addLocationButton.addEventListener('click', function () {
            var selectedLocation = locationSelect.value; // Get the selected location
            if (selectedLocation) {
                selectedLocationDisplay.textContent = selectedLocation; // Update the display
            }
            $('#pinMapModal').modal('hide'); // Close the modal
        });


    });
</script>