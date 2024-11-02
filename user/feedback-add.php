<?php
include '../inc/session_user.php';
include 'header.php';
?>


<style>
    .star-rating {
        display: flex;
        direction: row;
        cursor: pointer;
    }

    .star-rating .fa-star {
        color: #ccc;
        /* Default color */
    }

    .star-rating .fa-star.hover,
    .star-rating .fa-star.highlighted {
        color: #f39c12;
        /* Color for highlighted stars */
    }
</style>
<div class="content-wrapper">

    <div class="content-header">
        <section class="content">
            <button class="back-button" onclick="goBack()">
                <span class="material-icons">arrow_back</span> Back
            </button>
            <div class="container-fluid">
                <div class="row mt-5">
                    <div class="col m-auto">
                        <div class="card m-auto" style="background-color:#6CB4EE;">
                            <div class="card-header font-weight-bold" style="font-size:20px">
                                <i class="fas fa-comments"></i> Feedback
                            </div>
                            <div class="card-body">
                                <h5 class="font-weight-bold">Rate your experience</h5>
                                <div class="star-rating">
                                    <i class="far fa-star fa-2x" data-value="1"></i>
                                    <i class="far fa-star fa-2x" data-value="2"></i>
                                    <i class="far fa-star fa-2x" data-value="3"></i>
                                    <i class="far fa-star fa-2x" data-value="4"></i>
                                    <i class="far fa-star fa-2x" data-value="5"></i>
                                </div>
                                <input type="hidden" id="rating-input" name="rate" value="0">

                                <h5 class="font-weight-bold mt-3">Which part of the app needs improvement?</h5>
                                <select class="form-control" name="app_improvement[]" multiple="multiple"
                                    id="app_improvement">
                                    <option value="None">None</option>
                                    <option value="User Interface">User Interface</option>
                                    <option value="Security">Security</option>
                                    <option value="Privacy">Privacy</option>
                                    <option value="Loading Speed">Loading Speed</option>
                                    <option value="Community Content">Community Content</option>
                                    <option value="Functionality">Functionality</option>
                                    <option value="Accessibility">Accessibility</option>
                                </select>

                                <textarea class="form-control mt-2" placeholder="Add comment" rows="4" name="feedback"
                                    id="feedback-input"></textarea>
                                <div class="text-center mt-3">
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <button class="btn btn-dark btn-block add-feedback-btn"
                                            style="border-radius:25px; background-color:#002D62;" type="button"
                                            name="add_feedback">SUBMIT
                                            NOW</button>
                                    <?php else: ?>
                                        <a type="button" href="login.php" class="btn btn-dark btn-block"
                                            style="border-radius:25px; background-color:#002D62;">SUBMIT NOW</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <?php
    include 'footer.php';
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#app_improvement').select2({
                width: 'resolve',
                placeholder: "Select improvements",
                allowClear: true,
                theme: "classic"
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.star-rating .fa-star');
            const ratingInput = document.getElementById('rating-input');

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', () => {
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('hover');
                        } else {
                            s.classList.remove('hover');
                        }
                    });
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach(s => s.classList.remove('hover'));
                });

                star.addEventListener('click', () => {
                    ratingInput.value = index + 1;
                    stars.forEach((s, i) => {
                        if (i <= index) {
                            s.classList.add('highlighted');
                        } else {
                            s.classList.remove('highlighted');
                        }
                    });
                });
            });
        });

        $(document).ready(function () {
            // Handle star rating clicks
            $('.star-rating i').on('click', function () {
                const rating = $(this).data('value');
                $('#rating-input').val(rating);
                console.log("Rating selected: ", rating); // Log rating value
                $('.star-rating i').removeClass('fas').addClass('far'); // Reset stars
                $(this).prevAll().addBack().removeClass('far').addClass('fas'); // Fill stars
            });

            // AJAX request to submit feedback
            $('.add-feedback-btn').on('click', function () {
                const rating = $('#rating-input').val();
                const appImprovement = $('#app_improvement').val();
                const feedback = $('#feedback-input').val(); // Accessing the textarea correctly

                console.log("Submitting feedback..."); // Log submission
                console.log("Rating: ", rating, "App Improvement: ", appImprovement, "Feedback: ", feedback); // Log all inputs

                // Validate required fields
                if (rating === '0' || appImprovement.length === 0) {
                    toastr.error('Please provide a rating and select at least one area for improvement.');
                    return;
                }

                $.ajax({
                    url: 'api/feedback/add-feedback.php',
                    type: 'POST',
                    data: {
                        rating: rating,
                        app_improvement: appImprovement,
                        feedback: feedback,
                    },
                    success: function (response) {
                        // Ensure response is parsed correctly
                        try {
                            const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                            console.log("Response from server: ", jsonResponse); // Log server response
                            if (jsonResponse.status === 'success') {
                                toastr.success('Feedback submitted successfully!');
                                resetForm();
                            } else {
                                alert('Error: ' + jsonResponse.message);
                            }
                        } catch (error) {
                            console.error("Response parsing error: ", error); // Log parsing error
                            alert('An error occurred while processing the response.');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error: ", textStatus, errorThrown); // Log AJAX errors
                        alert('An error occurred while submitting your feedback. Please try again.');
                    }
                });
            });

            function resetForm() {
                $('#rating-input').val('0');
                $('#app_improvement').val(''); // Clear the Select2 value
                $('#app_improvement').trigger('change'); // Update Select2 display
                $('#feedback-input').val(''); // Clear the textarea
                $('.star-rating i').removeClass('fas').addClass('far'); // Reset stars
            }


        });

    </script>