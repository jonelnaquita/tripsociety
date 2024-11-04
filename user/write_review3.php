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

    .remove-image {
        background: none;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        border-radius: 50%;
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
                            <h5 class="font-weight-bold">Rate your experiences:</h5>
                            <div class="star-rating">
                                <i class="far fa-star fa-2x" data-value="1"></i>
                                <i class="far fa-star fa-2x" data-value="2"></i>
                                <i class="far fa-star fa-2x" data-value="3"></i>
                                <i class="far fa-star fa-2x" data-value="4"></i>
                                <i class="far fa-star fa-2x" data-value="5"></i>
                            </div>
                            <input type="hidden" id="rating-input" name="rating" value="0" required>

                            <h5 class="mt-3 font-weight-bold">Have you encountered any hazard?</h5>
                            <select class="form-control w-50" id="hazard-input" name="hazard" required>
                                <option value="">Select a hazard level</option>
                                <option value="No Hazard">No Hazard</option>
                                <option value="Very low hazard">Very low hazard</option>
                                <option value="Low hazard">Low hazard</option>
                                <option value="Moderate hazard">Moderate hazard</option>
                                <option value="High hazard">High hazard</option>
                                <option value="Extreme hazard">Extreme hazard</option>
                            </select>

                            <div class="mt-3">
                                <h5 class="font-weight-bold">Write your review</h5>
                                <textarea class="form-control" name="review" rows="4"
                                    placeholder="Share your thoughts..." required></textarea>
                            </div>

                            <div class="mt-3">
                                <h5 class="font-weight-bold">Share some photos of your visit</h5>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="image-preview" id="image-preview"></div>
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
            const previewContainer = $('#image-preview');
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
                        <button class="btn btn-danger btn-sm remove-image" style="position: absolute; top: 0; right: 0;">&times;</button>
                    </div>
                `;
                    previewContainer.append(imageHtml);
                };

                reader.readAsDataURL(file);
            }
        });

        // Handle removing images
        $(document).on('click', '.remove-image', function () {
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


        // AJAX request to submit review
        $('.submit-btn').on('click', function () {
            const rating = $('#rating-input').val();
            const hazard = $('#hazard-input').val();
            const review = $('textarea[name="review"]').val(); // Accessing the textarea correctly

            console.log("Submitting review..."); // Log submission
            console.log("Rating: ", rating, "Hazard: ", hazard, "Review: ", review); // Log all inputs

            // Validate required fields
            if (rating === '0' || hazard === '') {
                toastr.error('Please provide a rating and select a hazard level.');
                return;
            }

            const formData = new FormData();
            formData.append('id', $('#location-id').val());
            formData.append('rating', rating);
            formData.append('hazard', hazard);
            formData.append('review', review);

            // Append images to formData if available
            const files = $('#file-input')[0].files;
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            $.ajax({
                url: 'api/review/add-review.php',
                type: 'POST',
                data: formData,
                contentType: false, // Important for file uploads
                processData: false, // Important for file uploads
                success: function (response) {
                    console.log("Response from server: ", response); // Log server response
                    if (response.status === 'success') {
                        toastr.success('Review submitted successfully!', 'Success');
                        // Reset form fields
                        resetForm(); // Call the reset function
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown); // Log AJAX errors
                    toastr.error('An error occurred while submitting your review. Please try again.');
                }
            });
        });

        // Function to reset the form fields
        function resetForm() {
            $('#rating-input').val('0');
            $('#hazard-input').val('');
            $('textarea[name="review"]').val(''); // Clear the textarea
            $('.star-rating i').removeClass('fas').addClass('far'); // Reset stars
            $('#image-preview').empty(); // Clear image previews
            $('#file-input').val(''); // Reset file input
        }
    });

</script>