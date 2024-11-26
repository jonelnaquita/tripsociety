<div class="tab-pane fade" id="link2" role="tabpanel" aria-labelledby="link2-tab">
    <label class="font-weight-bold mt-2">Details</label>

    <div class="input-group" style="margin-top:-10px;">
        <div class="input-group-prepend">
            <span class="input-group-text bg-transparent border-0"><i class="fas fa-map-marker-alt"></i></span>
        </div>
        <input class="form-control form-control-border bg-transparent"
            value="<?php echo !empty($row['location']) ? $row['location'] : 'No City Selected'; ?>" readonly>
    </div>
    <br>



    <div id="reviews-container" style="margin-top:-15px;">
        <!-- Reviews will be dynamically inserted here -->
    </div>
</div>


<script>
    $(document).ready(function () {
        fetchReviews();

        function fetchReviews() {
            // Get user_id from the URL, if it exists
            const urlParams = new URLSearchParams(window.location.search);
            const userIdParam = urlParams.get('id');
            const requestUrl = userIdParam
                ? `api/review/fetch-reviews-user.php?id=${userIdParam}`
                : 'api/review/fetch-reviews-user.php';

            $.ajax({
                url: requestUrl, // Use dynamically generated URL
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

                        let likedClass = location.user_liked > 0 ? 'fas' : 'far';
                        let requestSent = location.request_sent > 0;
                        let isOwnReview = location.user_id === <?php echo $_SESSION['user'] ?>;
                        let imagesrc = '../admin/review_image/${img}';

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
                                    </div>
                                </div>
                                <div style="margin-bottom: 10px;" class="star-rating">
                                    ${'<i class="fas fa-star text-warning"></i>'.repeat(fullStars)}
                                    ${halfStar ? '<i class="fas fa-star-half-alt text-warning"></i>' : ''}
                                    ${'<i class="far fa-star text-warning"></i>'.repeat(emptyStars)}
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
                                        <button class="btn btn-light btn-sm add-like" style="font-size:13px;" data-id="${location.id}">
                                            <i class="${likedClass} fa-thumbs-up"></i>
                                            <span class="reaction-count">${location.like_count}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                        $('#reviews-container').append(reviewHTML);
                    });
                },
                error: function () {
                    alert('Failed to load reviews. Please try again.');
                }
            });
        }

        // Like button logic
        $(document).on('click', '.add-like', function () {
            let reviewId = $(this).data('id');
            let likeIcon = $(this).find('.fa-thumbs-up');
            let reactionCountElem = $(this).find('.reaction-count');
            let reactionCount = parseInt(reactionCountElem.text(), 10);
            let isLiked = likeIcon.hasClass('fas');

            if (isLiked) {
                likeIcon.removeClass('fas').addClass('far');
                reactionCountElem.text(reactionCount - 1);
            } else {
                likeIcon.removeClass('far').addClass('fas');
                reactionCountElem.text(reactionCount + 1);
            }

            $.ajax({
                url: 'api/review/add-like.php',
                method: 'POST',
                data: { review_id: reviewId },
                success: function () {
                    // Handle success response if needed
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>


<script>
    $(document).on('click', 'img[data-toggle="modal"]', function () {
        const imgSrc = $(this).data('src');
        $('#modalImage').attr('src', imgSrc);
        $('#customImageModal').modal('show');
    });
</script>