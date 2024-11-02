<?php
include '../inc/session_user.php';
include 'header.php';
?>

<style>
    /* Container for badges */
    .badges-container {
        margin-top: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    /* Badge Style */
    .improvement-badge {
        border: 1px solid #582fff;
        /* Border color */
        border-radius: 15px;
        padding: 2px 10px;
        font-size: 12px;
        font-weight: bold;
        color: #582fff;
        /* Text color matching border */
        background-color: transparent;
        /* No background color */
    }

    /* Container for admin response */
    .admin-response {
        margin-top: 15px;
        padding: 10px 15px;
        border-radius: 8px;
        background-color: #f5f5f5;
        box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        color: #333;
    }

    .admin-response p {
        margin: 0;
        line-height: 1.4;
    }

    /* Button Styling */
    .write-feedback-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: #582fff;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 20px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        z-index: 10;
    }

    /* Hover and Focus Effect */
    .write-feedback-btn:hover,
    .write-feedback-btn:focus {
        background-color: #4a25e0;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.25);
    }

    /* Button Resizing on Smaller Screens */
    @media (max-width: 767px) {
        .write-feedback-btn {
            padding: 8px 16px;
            top: 15px;
            right: 15px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h5 class="font-weight-bold">Feedbacks</h5>
                        <a href="feedback-add.php" class="btn btn-primary btn-sm d-flex align-items-center"
                            style="padding: 5px 10px; border-radius: 24px;">
                            <i class="far fa-edit mr-1"></i>
                            Write a feedback
                        </a>
                    </div>
                    <br>
                    <div id="reviews-container" style="margin-bottom: 100px;">
                        <!-- Reviews will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>



<script>
    $(document).ready(function () {
        fetchReviews();

        function fetchReviews() {
            $.ajax({
                url: 'api/feedback/fetch-feedback.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#reviews-container').html(''); // Clear existing reviews

                    // Show a message if no feedback is available
                    if (data.length === 0) {
                        $('#reviews-container').html(`
                            <div class="no-feedback-message material-card">
                                <p>No Feedback Available</p>
                            </div>
                        `);
                        return;
                    }

                    $.each(data, function (index, location) {
                        let dateOptions = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
                        let dateCreated = new Date(location.date_created).toLocaleDateString('en-US', dateOptions);

                        let fullStars = Math.floor(location.rate);
                        let halfStar = (location.rate - fullStars) >= 0.5 ? 1 : 0;
                        let emptyStars = 5 - (fullStars + halfStar);

                        // Generate badges for each app improvement entry
                        let improvementBadges = '';
                        if (location.app_improvement) {
                            let improvements = location.app_improvement.split(/,\s*/); // Split by comma or comma + space
                            improvements.forEach(improvement => {
                                improvementBadges += `<div class="improvement-badge">${improvement}</div>`;
                            });
                        }

                        // Generate admin response section if available
                        let adminResponseHTML = '';
                        if (location.admin_response) {
                            adminResponseHTML = `
                                <div class="admin-response">
                                    <p><strong>Admin Response:</strong></p>
                                    <p>${location.admin_response}</p>
                                </div>
                            `;
                        }

                        let reviewHTML = `
                        <div class="card mt-3" data-review-id="${location.id}" style="position: relative;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto" style="margin-top:-2px;">
                                        <div class="ml-2">
                                            <a href="profile.php?id=${location.user_id}">
                                                <img src="../admin/profile_image/${location.profile_img || '../dist/img/avatar2.png'}" 
                                                    class="rounded-circle" 
                                                    style="width: 40px; height: 40px; object-fit: cover;">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col" style="margin-top:-4px; margin-left:-10px;">
                                        <h6 class="font-weight-bold">
                                            ${location.name} ${location.status == 1 ? '<i class="fas fa-check-circle" style="color: #582fff; margin-left: 3px;" title="Verified"></i>' : ''}
                                        </h6>
                                        <div style="margin-top:-7px;" class="star-rating">
                                            ${'<i class="fas fa-star text-warning"></i>'.repeat(fullStars)}
                                            ${halfStar ? '<i class="fas fa-star-half-alt"></i>' : ''}
                                            ${'<i class="far fa-star text-warning"></i>'.repeat(emptyStars)}
                                        </div>
                                    </div>
                                </div> 
                                <div class="badges-container">
                                ${improvementBadges} <!-- Display individual badges for app improvements -->
                            </div>
                                <p class="ml-2 mr-2 mt-2">${location.feedback}</p>
                                ${adminResponseHTML} <!-- Display admin response if available -->
                            </div>
                        </div>
                    `;
                        $('#reviews-container').append(reviewHTML);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", error);
                    console.error("Status:", status);
                    console.error("Response:", xhr.responseText);
                }
            });
        };
    });
</script>




<?php
include 'footer.php';
?>