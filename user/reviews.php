<!DOCTYPE html>
<html lang="en">

<?php
include '../inc/session_user.php';
include 'header.php';
include 'modal/report.php';
include 'modal/review.php';
include 'modal/edit-report.php'; ?>

<head>
    <!--<link rel="stylesheet" href="assets/css/review.css">-->
    <link rel="stylesheet" href="assets/css/report.css">
</head>

<body>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <br>
                        <div id="reviews-container">
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
                    url: 'api/review/fetch-reviews.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#reviews-container').html(''); // Clear existing reviews

                        $.each(data, function (index, location) {
                            let imageArray = location.images ? location.images.split(',') : [];
                            let dateOptions = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
                            let dateCreated = new Date(location.date_created).toLocaleDateString('en-US', dateOptions);

                            let fullStars = Math.floor(location.rating);
                            let halfStar = (location.rating - fullStars) >= 0.5 ? 1 : 0;
                            let emptyStars = 5 - (fullStars + halfStar);

                            // Check if the user liked this review
                            let likedClass = location.user_liked > 0 ? 'fas' : 'far';

                            // Check if a request has been sent
                            let requestSent = location.request_sent > 0;
                            let isOwnReview = location.user_id === <?php echo $_SESSION['user'] ?>;

                            // Button for inviting companions
                            let companionButton = requestSent
                                ? `<span class="badge badge-primary" title="Request Sent" style="font-size: 10px;">Invite Sent</span>`
                                : !isOwnReview
                                    ? `<button class="btn btn-light btn-sm add-companion" style="border-radius:20px; font-size:13px;" data-id="${location.user_id}">
                                <img src="../img/companion.png" style="width:15px;">
                               </button>`
                                    : ''; // No button if it's the user's own review

                            let dropdownMenu = `
                                <div class="dropdown float-right">
                                    <button class="btn btn-white btn-sm border-0 dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        ${isOwnReview
                                    ? ` <a class="dropdown-item text-left" style="font-size:13px;" href="write_review3.php?id=${location.location_id}">
                                            Edit
                                        </a>
                                        <a class="dropdown-item text-left delete-review" 
                                            style="font-size:13px;" 
                                            href="#" 
                                            data-id="${location.id}" 
                                            data-toggle="modal" 
                                            data-target="#deleteConfirmationModal"> 
                                                Delete
                                        </a>`
                                    : `<a class="dropdown-item text-center" style="font-size:13px;" href="#" data-id="${location.id}" data-toggle="modal" data-target="#reportPostModal">
                                                Report Post
                                            </a>`}
                                    </div>
                                </div>
                                `;


                            let reviewHTML = `
                        <div class="card mt-3 elevation-2" data-review-id="${location.id}">
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
                                        <p style="margin-top:-7px; font-size:12px;" class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> ${location.location_name}, ${location.city}
                                        </p>
                                        <div style="margin-top:-7px;" class="star-rating">
                                            ${'<i class="fas fa-star text-warning"></i>'.repeat(fullStars)}
                                            ${halfStar ? '<i class="fas fa-star-half-alt text-warning"></i>' : ''}
                                            ${'<i class="far fa-star text-warning"></i>'.repeat(emptyStars)}
                                        </div>
                                    </div>
                                    ${dropdownMenu} <!-- Include the dropdown menu here -->
                                </div> 
                                <p class="ml-2 mr-2">${location.review}</p>
                                <div class="container">
                                    <div class="overflow-auto">
                                        <div class="d-flex">
                                            ${imageArray.map(img => img ? `
                                                <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                    <div class="d-flex justify-content-center" style="height: 0; padding-bottom: 100%; position: relative;">
                                                        <!-- Wrap the image in an anchor tag for Fancybox -->
                                                        <a href="../admin/review_image/${img}" data-fancybox="gallery">
                                                            <img src="../admin/review_image/${img}" alt="Image" class="img-fluid rounded"
                                                                style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; object-fit: cover;">
                                                        </a>
                                                    </div>
                                                </div>
                                            ` : '').join('')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col text-left">
                                        <p class="text-secondary" style="font-size:15px;">written ${dateCreated}</p>
                                    </div>
                                    <div class="col text-right">
                                        ${companionButton} <!-- Conditional badge/button here -->
                                        <button class="btn btn-light btn-sm add-like" style="font-size:13px;" data-id="${location.id}">
                                            <i class="${likedClass} fa-thumbs-up"></i>
                                            <span class="reaction-count">${location.like_count}</span> <!-- Display like count here -->
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                            $('#reviews-container').append(reviewHTML);
                        });

                        // Event delegation for dynamically loaded elements
                        $(document).on('click', '.add-companion', function () {
                            let companionId = $(this).data('id'); // Get companion ID from data-id
                            $('#disclaimerModal').data('companionId', companionId); // Store companion ID in the modal
                            $('#disclaimerModal').modal('show'); // Show the modal
                        });

                        // Handle Proceed button click in Disclaimer Modal
                        $(document).on('click', '.add-companion-btn', function () {
                            let companionId = $('#disclaimerModal').data('companionId'); // Get stored companion ID
                            if (companionId) { // Ensure companionId exists
                                $.ajax({
                                    url: 'api/review/add-companion.php',
                                    method: 'POST',
                                    data: { companion_id: companionId },
                                    success: function (response) {
                                        $(`button.add-companion[data-id="${companionId}"]`).replaceWith(`<span class="badge badge-primary" title="Request Sent" style="font-size: 10px;">Invite Sent</span>`);
                                        $('#disclaimerModal').modal('hide');
                                    },
                                    error: function (xhr, status, error) {
                                        alert('An error occurred. Please try again.');
                                    }
                                });
                            }
                        });
                    }
                });

                // Like button logic
                $(document).on('click', '.add-like', function () {
                    let reviewId = $(this).data('id');
                    let likeIcon = $(this).find('.fa-thumbs-up');
                    let reactionCountElem = $(this).find('.reaction-count');
                    let reactionCount = parseInt(reactionCountElem.text(), 10);
                    let isLiked = likeIcon.hasClass('fas'); // Check if it's currently liked

                    // Toggle like status and update UI immediately
                    if (isLiked) {
                        likeIcon.removeClass('fas').addClass('far'); // Change to outline icon
                        reactionCountElem.text(reactionCount - 1); // Decrement the like count
                    } else {
                        likeIcon.removeClass('far').addClass('fas'); // Change to filled icon
                        reactionCountElem.text(reactionCount + 1); // Increment the like count
                    }

                    // Send the like status to the server
                    $.ajax({
                        url: 'api/review/add-like.php',
                        method: 'POST',
                        data: { review_id: reviewId },
                        success: function (response) {
                            // Handle success response if needed
                        },
                        error: function () {
                            alert('An error occurred. Please try again.');
                        }
                    });
                });
            };


            let reviewIdToDelete = null;

            // Trigger when delete button is clicked
            $(document).on('click', '.delete-review', function () {
                reviewIdToDelete = $(this).data('id'); // Fetch the data-id
                console.log('Review ID:', reviewIdToDelete); // Debugging
            });

            // Confirm delete action
            $('#confirmDeleteReviewBtn').on('click', function () {
                if (reviewIdToDelete) {
                    $.ajax({
                        url: 'api/review/delete-review2.php',
                        type: 'POST',
                        data: { id: reviewIdToDelete },
                        success: function (response) {
                            console.log('Server response:', response); // Debugging
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                $(`[data-id="${reviewIdToDelete}"]`).closest('.review-item').remove();
                                $('#deleteConfirmationModal').modal('hide');
                                fetchReviews();
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function () {
                            toastr.error('An error occurred while trying to delete the review.');
                        }
                    });
                } else {
                    console.error('No review ID to delete.');
                }
            });

        });
    </script>

</body>

</html>


<?php
include 'footer.php';
?>

<!-- uPDATE sTATUS Modal-->
<div class="modal fade" id="disclaimerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">


            <div class="modal-body m-2">
                <h3 class="text-center font-weight-bold">Disclaimer</h3>
                <h5 class="text-center font-weight-bold">Safety of Accepting a Travel Companion</h5>
                <p>By choosing to accept a travel companion, you acknowledge and agree that you are solely responsible
                    for your personal safety. We recommend conducting thorough background checks, meeting in public
                    places before traveling, and informing family or friends of your travel plans and companion details.
                    Our platform does not vet travel companions and cannot guarantee their trustworthiness. Use caution
                    and good judgment when making travel arrangements. We are not liable for any incidents, accidents,
                    or disputes that may arise from traveling with a companion met through our service.</p>
                <div class="text-center">
                    <button type="button" class="btn btn-primary add-companion-btn">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap Modal for image preview -->
<div class="modal fade" id="customImageModal" tabindex="-1" role="dialog" aria-labelledby="customImageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content transparent-modal">
            <div class="modal-body">
                <img id="modalImage" src="" alt="Preview" class="img-fluid" style="border-radius: 10px;">
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                style="position: absolute; top: 10px; right: 10px; color: white;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>

<script>
    function previewImage(src) {
        const modalImage = document.getElementById("modalImage");
        modalImage.src = src;
        $('#customImageModal').modal('show'); // Show the Bootstrap modal
    }
</script>

<!-- Add Report -->
<script>
    $(document).on('click', '[data-toggle="modal"]', function () {
        var postId = $(this).data('id');
        $('#postIdInput').val(postId); // Set the post ID in the hidden input
    });

    $('#submitReport').on('click', function () {
        var postId = $('#postIdInput').val();
        var userId = $('input[name="user_id"]').val();
        var violation = $('#violationSelect').val();

        if (violation) {
            $.ajax({
                url: 'api/review/add-report.php', // The PHP script to handle the report
                type: 'POST',
                data: {
                    post_id: postId,
                    user_id: userId,
                    violation: violation
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#reportPostModal').modal('hide'); // Hide the modal
                    } else {
                        toasr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            toastr.error('Please select a violation reason.');
        }
    });
</script>

<script>
    $(document).ready(function () {

    });
</script>