<?php
include '../inc/session_user.php';
include 'header.php';
include '../inc/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    function getLocation($pdo, $id)
    {
        $query = "SELECT * FROM tbl_location WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $locations = getLocation($pdo, $id);

    if (!empty($locations)) {
        foreach ($locations as $location) {
            $name = htmlspecialchars($location['location_name']);
            $imageList = htmlspecialchars($location['image']);
            $imageArray = explode(',', $imageList);
            $firstImage = isset($imageArray[0]) ? $imageArray[0] : 'default.jpg';
        }
    }
}
?>

<style>
    .file-input {
        display: none;
    }

    .file-input-label {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        border-radius: 5px;
        color: #333;
    }

    .file-input-label i {
        margin-right: 5px;
    }

    .star-rating {
        display: flex;
        direction: row;
        cursor: pointer;
        margin-bottom: 15px;
    }

    .star-rating .fa-star {
        color: blue;
    }

    .star-rating .fa-star.hover,
    .star-rating .fa-star.highlighted {
        color: blue;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .image-preview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .image-upload {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .image-upload img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .card-header {
        background: #582fff;
    }

    .submit-btn {
        background-color: #582fff;
        color: white;
    }

    .submit-btn:hover {
        background-color: #001F4D;
    }

    .image-wrapper {
        position: relative;
        display: inline-block;
        margin: 5px;
    }

    .image-container {
        width: 100px;
        height: 100px;
        position: relative;
        /* Ensures absolute child elements are positioned relative to this container */
        border-radius: 5px;
        /* Optional: Matching img-thumbnail radius */
        overflow: hidden;
        /* Keeps elements contained within the image boundary */
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Ensures the image fits perfectly without distortion */
        border-radius: 5px;
        /* Matches the container's corner radius */
    }

    .delete-image {
        width: 18px;
        /* Adjust for desired button size */
        height: 18px;
        /* Keep it circular */
        position: absolute;
        /* Positioned within the container */
        top: 2px;
        /* Space from the top */
        right: 2px;
        /* Space from the right */
        padding: 0;
        font-size: 11px;
        /* Icon size */
        border-radius: 50%;
        background-color: rgba(220, 53, 69, 0.8);
        /* Semi-transparent red */
        color: #fff;
        border: none;
        /* Removes default border */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .delete-image:hover {
        background-color: rgba(220, 53, 69, 1);
        /* Solid red on hover */
    }

    .delete-image i {
        line-height: 1;
        /* Proper alignment of the icon */
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

<div class="content-wrapper">
    <button class="back-button" onclick="goBack()">
        <span class="material-icons">arrow_back</span> Back
    </button>

    <section class="content overflow-hidden" style="height: auto; margin-bottom: 50px;">
        <div class="container-fluid">
            <input type="hidden" value="<?php echo htmlspecialchars($_GET['id']); ?>" name="id" id="location-id">
            <div class="row mt-3">
                <div class="col">
                    <div class="card mt-4 rounded-0 shadow">
                        <div class="card-header rounded-0">
                            <div class="row">
                                <div class="col-4 m-auto">
                                    <img src="../admin/images/<?php echo $firstImage; ?>" class="img-fluid rounded"
                                        alt="<?php echo htmlspecialchars($name); ?>">
                                </div>
                                <div class="col-8 m-auto">
                                    <h4 class="font-weight-bold text-white mt-2">
                                        <?php echo htmlspecialchars($name); ?>
                                    </h4>
                                    <p class="text-white" style="font-size:13px; margin-top:-6px;">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($location['city']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="font-weight-bold mb-4">Rate Your Experiences</h4>

                            <div class="star-rating">
                                <i class="far fa-star fa-2x" data-value="1"></i>
                                <i class="far fa-star fa-2x" data-value="2"></i>
                                <i class="far fa-star fa-2x" data-value="3"></i>
                                <i class="far fa-star fa-2x" data-value="4"></i>
                                <i class="far fa-star fa-2x" data-value="5"></i>
                            </div>
                            <input type="hidden" id="rating-input" name="rating" value="0" required>

                            <!-- Question 1 -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold">1. Accessibility and Safety of Pathways</h5>
                                <p>Are the pathways, roads, or trails well-maintained and safe for visitors to walk or
                                    travel on without significant risks (e.g., steep drops, slippery surfaces, or
                                    obstacles)?</p>
                                <div>
                                    <label class="d-block"><input type="radio" name="q1" value="0" required> No
                                        Hazard</label>
                                    <label class="d-block"><input type="radio" name="q1" value="1" required> Very
                                        Low</label>
                                    <label class="d-block"><input type="radio" name="q1" value="2" required> Low</label>
                                    <label class="d-block"><input type="radio" name="q1" value="3" required>
                                        Moderate</label>
                                    <label class="d-block"><input type="radio" name="q1" value="4" required>
                                        High</label>
                                    <label class="d-block"><input type="radio" name="q1" value="5" required>
                                        Extreme</label>
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold">2. Weather and Environmental Conditions</h5>
                                <p>Are there any weather or environmental risks at this destination, such as extreme
                                    temperatures, sudden storms, or hazardous terrain?</p>
                                <div>
                                    <label class="d-block"><input type="radio" name="q2" value="0" required> No
                                        Hazard</label>
                                    <label class="d-block"><input type="radio" name="q2" value="1" required> Very
                                        Low</label>
                                    <label class="d-block"><input type="radio" name="q2" value="2" required> Low</label>
                                    <label class="d-block"><input type="radio" name="q2" value="3" required>
                                        Moderate</label>
                                    <label class="d-block"><input type="radio" name="q2" value="4" required>
                                        High</label>
                                    <label class="d-block"><input type="radio" name="q2" value="5" required>
                                        Extreme</label>
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold">3. Availability of Safety Measures</h5>
                                <p>Does the destination have adequate safety measures in place, such as warning signs,
                                    safety equipment, emergency exits, or trained staff to handle emergencies?</p>
                                <div>
                                    <label class="d-block"><input type="radio" name="q3" value="0" required> No
                                        Hazard</label>
                                    <label class="d-block"><input type="radio" name="q3" value="1" required> Very
                                        Low</label>
                                    <label class="d-block"><input type="radio" name="q3" value="2" required> Low</label>
                                    <label class="d-block"><input type="radio" name="q3" value="3" required>
                                        Moderate</label>
                                    <label class="d-block"><input type="radio" name="q3" value="4" required>
                                        High</label>
                                    <label class="d-block"><input type="radio" name="q3" value="5" required>
                                        Extreme</label>
                                </div>
                            </div>

                            <!-- Question 4 -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold">4. Health and Sanitation Conditions</h5>
                                <p>Are there any health risks related to the destination, such as contaminated water,
                                    lack of proper sanitation facilities, or the spread of diseases?</p>
                                <div>
                                    <label class="d-block"><input type="radio" name="q4" value="0" required> No
                                        Hazard</label>
                                    <label class="d-block"><input type="radio" name="q4" value="1" required> Very
                                        Low</label>
                                    <label class="d-block"><input type="radio" name="q4" value="2" required> Low</label>
                                    <label class="d-block"><input type="radio" name="q4" value="3" required>
                                        Moderate</label>
                                    <label class="d-block"><input type="radio" name="q4" value="4" required>
                                        High</label>
                                    <label class="d-block"><input type="radio" name="q4" value="5" required>
                                        Extreme</label>
                                </div>
                            </div>

                            <!-- Question 5 -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold">5. Crowd Control and Security</h5>
                                <p>Are there sufficient crowd control and security measures in place to ensure the
                                    safety of visitors, especially during busy times or events?</p>
                                <div>
                                    <label class="d-block"><input type="radio" name="q5" value="0" required> No
                                        Hazard</label>
                                    <label class="d-block"><input type="radio" name="q5" value="1" required> Very
                                        Low</label>
                                    <label class="d-block"><input type="radio" name="q5" value="2" required> Low</label>
                                    <label class="d-block"><input type="radio" name="q5" value="3" required>
                                        Moderate</label>
                                    <label class="d-block"><input type="radio" name="q5" value="4" required>
                                        High</label>
                                    <label class="d-block"><input type="radio" name="q5" value="5" required>
                                        Extreme</label>
                                </div>
                            </div>

                            <div class="mt-3">
                                <h5 class="font-weight-bold">Write your review</h5>
                                <textarea class="form-control" name="review" rows="4"
                                    placeholder="Share your thoughts..." required></textarea>
                            </div>

                            <div class="mt-3">
                                <h5 class="font-weight-bold">Share some photos of your visit</h5>
                                <div class="row">
                                    <div class="col-auto">
                                        <div id="image-preview" style="display: none;"></div>
                                        <div id="image-upload"></div>
                                    </div>
                                    <div class="col-auto">
                                        <label for="file-input" class="file-input-label ml-2" style="margin-top:25px;">
                                            <i class="far fa-images fa-2x text-secondary"></i>
                                            <i class="fas fa-plus-circle shadow"
                                                style="margin-left:-10px; margin-top:14px;"></i>
                                            <input type="file" name="images[]" accept="image/*" id="file-input"
                                                class="file-input" multiple required>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" name="add_review" class="btn btn-primary submit-btn"
                                style="border-radius: 30px; padding: 12px 20px; margin-bottom: 50px;">
                                <i class="fas fa-check-circle"></i> Submit Review
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<?php include 'footer.php'; ?>

<script>
    $(document).ready(function () {
        $('#file-input').on('change', function () {
            const files = this.files;
            const previewContainer = $('#image-upload');
            previewContainer.empty(); // Clear previous previews

            // Check the number of files selected
            const maxImages = 4;
            if (files.length > maxImages) {
                alert(`You can only upload a maximum of ${maxImages} images.`);
                // Reset the input to prevent excess images from being processed
                this.value = '';
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const imageHtml = `
                    <div class="image-wrapper position-relative">
                        <img src="${e.target.result}" alt="Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                        <button class="btn btn-danger btn-sm delete-image" style="position: absolute; top: 40; right: 40;">&times;</button>
                    </div>
                `;
                    previewContainer.append(imageHtml);
                };

                reader.readAsDataURL(file);
            }
        });

        // Handle removing images
        $(document).on('click', '.delete-image', function () {
            $(this).closest('.image-wrapper').remove();
        });
    });
</script>


<script>
    $(document).ready(function () {
        $('.star-rating i').on('click', function () {
            const rating = $(this).data('value');
            $('#rating-input').val(rating);
            console.log("Rating selected: ", rating); // Log rating value
            $('.star-rating i').removeClass('fas blue-star').addClass('far'); // Reset stars
            $(this).prevAll().addBack().removeClass('far').addClass('fas blue-star'); // Fill stars
        });

        $('.submit-btn').on('click', function () {
            // Collect ratings from q1 to q5
            const q1 = parseInt($('input[name="q1"]:checked').val() || 0);
            const q2 = parseInt($('input[name="q2"]:checked').val() || 0);
            const q3 = parseInt($('input[name="q3"]:checked').val() || 0);
            const q4 = parseInt($('input[name="q4"]:checked').val() || 0);
            const q5 = parseInt($('input[name="q5"]:checked').val() || 0);

            // Create a comma-separated string of hazard levels
            const hazardLevels = `${q1},${q2},${q3},${q4},${q5}`;

            // Calculate the average rating
            const averageRating = (q1 + q2 + q3 + q4 + q5) / 5;

            // Collect other form data
            const review = $('textarea[name="review"]').val();
            const locationId = $('#location-id').val();
            const review_rating = $('#rating-input').val();

            // Validate required fields
            if (isNaN(averageRating) || !review || !locationId || !review_rating) {
                toastr.error('Please fill in all required fields before submitting.');
                return;
            }

            // Prepare form data for AJAX
            const formData = new FormData();
            formData.append('location_id', locationId);
            formData.append('average_rating', averageRating.toFixed(2)); // Save as a float with 2 decimals
            formData.append('hazard_levels', hazardLevels); // Add hazard levels
            formData.append('review', review);
            formData.append('rating', review_rating);

            // Add uploaded images
            const files = $('#file-input')[0].files;
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            // Submit form via AJAX
            $.ajax({
                url: 'api/review/add-review.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status === 'success') {
                        //toastr.success(response.message);
                        location.reload();
                        //resetForm();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('An error occurred while submitting the review.');
                }
            });
        });

    });
</script>


<script>
    $(document).ready(function () {
        const locationId = $('#location-id').val();
        const userId = <?php echo json_encode($_SESSION['user']); ?>;

        // Function to load existing review
        function loadReview() {
            $.ajax({
                url: "api/review/check-review.php",
                type: "POST",
                data: {
                    location_id: locationId,
                    user_id: userId
                },
                dataType: "json",
                success: function (response) {
                    console.log("Fetched Response:", response);

                    if (response.status === "found") {
                        // Populate rating, questions, and review
                        $('#rating-input').val(response.rating);
                        $('.star-rating i').removeClass('fas').addClass('far'); // Reset stars
                        $('.star-rating i:lt(' + response.rating + ')').addClass('fas'); // Highlight stars

                        $('input[name="q1"][value="' + response.q1 + '"]').prop('checked', true);
                        $('input[name="q2"][value="' + response.q2 + '"]').prop('checked', true);
                        $('input[name="q3"][value="' + response.q3 + '"]').prop('checked', true);
                        $('input[name="q4"][value="' + response.q4 + '"]').prop('checked', true);
                        $('input[name="q5"][value="' + response.q5 + '"]').prop('checked', true);

                        $('textarea[name="review"]').val(response.review);

                        // Handle image previews with delete button
                        if (response.images && response.images.length > 0 && response.images.some(image => image.trim() !== '')) {
                            console.log("Images found:", response.images); // Debugging log
                            console.log("Review ID:", response.id);
                            const imagePreview = response.images.map(image => {
                                const imagePath = `../admin/review_image/${image}`;
                                return `
                                <div class="image-container position-relative d-inline-block m-1">
                                    <img src="${imagePath}" class="img-thumbnail" style="width: 100px; height: 100px;">
                                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-image remove-image rounded-circle" data-image="${image}" data-review-id="${response.id}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                            `;
                            }).join('');
                            $('#image-preview').html(imagePreview).show(); // Append images and show container
                        } else {
                            console.log("No valid images found, hiding image preview"); // Debugging log
                            $('#image-preview').empty().hide(); // Clear and hide container if no valid images
                        }

                        // Update the submit button to say 'Update Review'
                        $('.submit-btn').html('<i class="fas fa-check-circle"></i> Update Review');
                    } else {
                        console.log("No review found");
                        // Clear form if no review is found
                        $('#rating-input').val('');
                        $('.star-rating i').removeClass('fas').addClass('far'); // Reset stars
                        $('input[name="q1"], input[name="q2"], input[name="q3"], input[name="q4"], input[name="q5"]').prop('checked', false);
                        $('textarea[name="review"]').val('');
                        $('#image-preview').empty().hide(); // Clear and hide image container
                        $('.submit-btn').html('<i class="fas fa-check-circle"></i> Submit Review');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.error("Response Text:", xhr.responseText);
                    alert("An error occurred while checking the review. Please try again later.");
                }
            });
        }

        // Real-time trigger: Call loadReview when location changes
        $('#location-id').on('change', function () {
            loadReview();
        });

        // Initial check on page load
        loadReview();

        // Delete image on button click
        $(document).on('click', '.remove-image', function () {
            const imageName = $(this).data('image');
            const reviewId = $(this).data('review-id');

            $.ajax({
                url: 'api/review/delete-image.php',
                type: 'POST',
                data: {
                    review_id: reviewId,
                    image_name: imageName
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Remove the image preview from the DOM
                        $(`button[data-image="${imageName}"]`).closest('.image-container').remove();
                        console.log('Image deleted successfully');
                    } else {
                        alert('Failed to delete image: ' + response.message);  // Show detailed error message from PHP
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                    console.error("Response Text:", xhr.responseText);  // Log the response text for better debugging
                    alert("An error occurred while deleting the image. Please try again later.");
                }
            });
        });

    });


</script>