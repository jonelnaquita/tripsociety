<?php
include '../inc/session_user.php';
include 'header.php';
?>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<style>
    .post-card {
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        padding: 15px;
    }

    .post-header img {
        border-radius: 50%;
        width: 50px;
        height: 50px;
    }

    .post-body img {
        width: 100%;
        border-radius: 8px;
        margin-top: 10px;
    }

    .img-container {
        position: relative;
        width: 100%;
        height: 300px;
        /* Default height for larger screens */
        overflow: hidden;
        border-radius: 8px;
    }

    /* Media Query for Mobile Devices */
    @media (max-width: 768px) {
        .img-container {
            height: 180px;
            /* Height for mobile view */
        }
    }


    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .post-actions button {
        color: #6c757d;
        background: none;
        border: none;
        margin-right: 10px;
    }

    .comment-section {
        background-color: #f7f7f7;
        padding: 15px;
        border-radius: 8px;
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
    }
</style>

<div class="content-wrapper" style="margin-top: -20px; margin-bottom: 50px;">
    <div class="container mt-5">
        <!-- Post Card -->
        <div class="post-card">
            <div class="post-body"></div>
            <div class="post-actions"></div>
            <div class="comment-section"></div>

            <!-- Modal for Image Preview -->
            <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog"
                aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body position-relative p-0">
                            <!-- Close Button -->
                            <button type="button" class="close position-absolute" data-dismiss="modal"
                                aria-label="Close" style="top: 10px; right: 15px; color: white; font-size: 1.5rem;">
                                &times;
                            </button>
                            <!-- Carousel for Sliding Images -->
                            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner" id="carouselImagesContainer">
                                    <!-- Images will be inserted here by JavaScript -->
                                </div>
                                <!-- Carousel Controls -->
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
                                    data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
                                    data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
include 'footer.php';
?>

<script>
    $(document).ready(function () {
        // Get post_id from the URL
        var urlParams = new URLSearchParams(window.location.search);
        var post_id = urlParams.get('id'); // Assuming the URL is like yourpage.php?id=65

        // Function to fetch post details
        function fetchPost() {
            $.ajax({
                url: 'api/post/fetch-post.php',
                type: 'POST',
                data: { post_id: post_id },
                dataType: 'json',
                success: function (response) {
                    if (response.post) {
                        // Display post details
                        var post = response.post;

                        // Clear existing content
                        $('.post-body').empty();
                        $('.comment-section').empty();

                        // Display post header
                        var locationHtml = post.location && post.location !== 'null' ? `<small class="text-muted">@${post.username} • ${post.location}</small>` : `<small class="text-muted">@${post.username}</small>`;
                        var postHeaderHtml = `
                            <div class="post-header d-flex align-items-center mb-3">
                                <img src="../admin/profile_image/${post.profile_img}" alt="User Profile Picture" class="img-circle" style="width: 50px; height:50px; object-fit: cover; border-radius: 50%;">
                                <div class="ms-3 ml-2">
                                    <h6 class="mb-0 fw-bold">${post.name}</h6>
                                    ${locationHtml}
                                </div>
                            </div>
                        `;
                        $('.post-body').append(postHeaderHtml);

                        // Display post body
                        var postBodyHtml = `
                            <div class="post-body">
                                <p class="mb-2">${post.post}</p>
                                <div class="row g-2">
                        `;

                        // Split the image string and display each image
                        var images = post.image.split(',');
                        var hasImages = false; // Flag to check if any image exists
                        images.forEach(function (img) {
                            if (img.trim()) { // Check if the image name is not empty
                                hasImages = true;
                                postBodyHtml += `
                                    <div class="col-6">
                                        <div class="img-container">
                                            <img src="../admin/post_image/${img.trim()}" class="img-fluid rounded gallery-image" alt="Post Image">
                                        </div>
                                    </div>
                                `;
                            }
                        });

                        postBodyHtml += `
                                </div>
                            </div>
                        `;
                        $('.post-body').append(postBodyHtml);

                        // Check if user has reacted to the post
                        var hasReacted = post.user_reacted;
                        var heartIconClass = hasReacted ? 'fas' : 'far';
                        var reactionCount = post.reaction_count || 0;
                        var postActionsHtml = `
                            <div class="d-flex justify-content-start mt-3">
                                <div class="d-inline-flex align-items-start">
                                    <button class="btn-count btn btn-light bg-transparent btn-sm border-0 reactionButton" data-reacted="${hasReacted}">
                                        <i class="${heartIconClass} fa-heart text-danger" style="font-size:15px;"></i>
                                    </button>
                                    <span class="badge bg-secondary position-relative" style="font-size:10px; top: -0.5em; margin-left:-25px;">${reactionCount}</span>
                                </div>
                                <button class="btn">
                                    <i class="far fa-comment-alt text-dark" style="font-size:15px;"></i> Comment
                                </button>
                            </div>
                        `;
                        $('.post-actions').append(postActionsHtml);

                        // Display comments
                        var commentsHtml = '<div class="comment-section"><h6 class="fw-bold">Comments</h6>';
                        if (response.comments.length > 0) { // Check if there are comments
                            $.each(response.comments, function (index, comment) {
                                commentsHtml += `
                                    <div class="d-flex mb-2">
                                        <img src="../admin/profile_image/${comment.profile_img}" alt="User Profile Picture" class="me-2 img-circle" style="width:40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                        <div class="ml-2">
                                            <h6 class="mb-0">${comment.name} <small class="text-muted">@${comment.username}</small></h6>
                                            <p class="mb-1">${comment.message}</p>
                                            <small class="text-muted">${comment.date_created}</small>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            commentsHtml += '<p class="text-muted">No comments available.</p>'; // Message for no comments
                        }
                        commentsHtml += `</div>
                            <div class="d-flex align-items-center mt-3">
                                <input type="text" class="form-control me-2 comment-input" placeholder="Write a comment..." style="border-radius: 20px;">
                                <button class="btn btn-light add-comment" style="border-radius: 20px;">
                                    <i class="fas fa-paper-plane text-primary"></i>
                                </button>
                            </div>
                        `;
                        $('.comment-section').append(commentsHtml);

                        // Add event listener for the add comment button
                        $('.add-comment').on('click', function () {
                            var message = $('.comment-input').val().trim();
                            if (message) {
                                // AJAX request to add comment
                                $.ajax({
                                    url: 'api/home/add-comment.php',
                                    type: 'POST',
                                    data: {
                                        post_id: post_id,
                                        message: message
                                    },
                                    dataType: 'json',
                                    success: function (response) {
                                        if (response.status === 'success') {
                                            fetchPost(); // Fetch post again to refresh comments
                                        } else {
                                            alert(response.message);
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        console.error("Error adding comment:", textStatus, errorThrown);
                                    }
                                });
                            } else {
                                alert("Please enter a comment before submitting.");
                            }
                        });

                        // Initialize gallery images for preview
                        initializeImagePreview();
                    } else {
                        alert("Post not found.");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Error fetching post details:", textStatus, errorThrown);
                }
            });
        }

        // Function to initialize image preview
        function initializeImagePreview() {
            const galleryImages = document.querySelectorAll('.gallery-image');
            const carouselContainer = document.getElementById('carouselImagesContainer');
            const imagePreviewModal = $('#imagePreviewModal');

            galleryImages.forEach((img, index) => {
                img.addEventListener('click', () => {
                    carouselContainer.innerHTML = '';
                    galleryImages.forEach((image, idx) => {
                        const isActive = index === idx ? 'active' : '';
                        const carouselItem = `
                            <div class="carousel-item ${isActive}">
                                <img src="${image.src}" class="d-block w-100" alt="Slide ${idx + 1}" style="object-fit: cover;">
                            </div>`;
                        carouselContainer.innerHTML += carouselItem;
                    });
                    imagePreviewModal.modal('show');
                });
            });
        }

        // Initial fetch of the post details
        fetchPost();
    });
</script>