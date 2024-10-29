<?php
include 'modal/comment.php';
include 'modal/home.php';
?>

<link rel="stylesheet" href="assets/css/post.css">

<style>
    @media (max-width: 576px) {
        .comment-section-modal-dialog {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .comment-section-modal-content {
            height: 100%;
            border-radius: 0;
        }

        .modal-edit-post {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .modal-edit-post .modal-content {
            height: 100%;
            border-radius: 0;
            margin: 0;
            padding: 0;
        }

        .modal-edit-post .modal-body {
            overflow-y: auto;
        }

        .comment-section-modal-body {
            overflow-y: auto;
        }
    }

    .img-circle {
        border-radius: 50%;
        /* Makes the image circular */
        overflow: hidden;
        /* Ensures that any overflow is hidden */
    }
</style>


<div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
    <label class="font-weight-bold mt-2">Details</label>
    <div class="input-group" style="margin-top:-10px;">
        <div class="input-group-prepend">
            <span class="input-group-text bg-transparent border-0"><i class="fas fa-map-marker-alt"></i></span>
        </div>
        <input class="form-control form-control-border bg-transparent" value="<?php echo $row['location']; ?>" readonly>
    </div>
    <br>
    <?php
    if (isset($_SESSION['user'])) {
        include '../inc/config.php';
        // session_start();
        $userId = $_SESSION['user']; // Assuming user ID is stored in session
    
        // Fetch posts with user information
        $pdo_statement = $pdo->prepare("SELECT *, p.date_created as date, p.id as id, p.location as location 
                                FROM tbl_post p 
                                LEFT JOIN tbl_user u ON u.id = p.user_id where p.user_id = " . $userId . "
                                ORDER BY p.id DESC");
        $pdo_statement->execute();
        $posts = $pdo_statement->fetchAll();

        if (!empty($posts)) {


            foreach ($posts as $post) {
                $date = $post['date'];

                // Set the timezone to Manila
                $manilaTimezone = new DateTimeZone('Asia/Manila');

                // Create DateTime objects for the posted date and the current date
                $datePosted = new DateTime($date, $manilaTimezone);
                $now = new DateTime('now', $manilaTimezone);

                // Calculate the interval
                $interval = $datePosted->diff($now);

                // Determine the time difference string
                $timeDifference = '';
                if ($interval->y > 0) {
                    $timeDifference = $interval->y . 'y';
                } elseif ($interval->m > 0) {
                    $timeDifference = $interval->m . 'm';
                } elseif ($interval->days > 0) {
                    $timeDifference = $interval->days . 'd';
                } elseif ($interval->h > 0) {
                    $timeDifference = $interval->h . 'h';
                } elseif ($interval->i > 0) {
                    $timeDifference = $interval->i . 'm';
                } else {
                    $timeDifference = $interval->s . 's';
                }



                // Check if the user has reacted to this post
                $reaction_statement = $pdo->prepare("SELECT 1 FROM tbl_reaction WHERE user_id = ? AND post_id = ?");
                $reaction_statement->execute([$userId, $post['id']]);
                $has_reacted = $reaction_statement->fetchColumn();

                // Determine the icon class based on whether the user has reacted
                $icon_class = $has_reacted ? 'fas fa-heart' : 'far fa-heart';

                $count_statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_reaction WHERE post_id = ?");
                $count_statement->execute([$post['id']]);
                $reaction_count = $count_statement->fetchColumn();

                $count_statement1 = $pdo->prepare("SELECT COUNT(*) FROM tbl_post_comment WHERE post_id = ?");
                $count_statement1->execute([$post['id']]);
                $comment_count = $count_statement1->fetchColumn();

                $image_statement = $pdo->prepare("SELECT image FROM tbl_post WHERE id = ?");
                $image_statement->execute([$post['id']]);
                $post_images = $image_statement->fetchColumn();
                $imageFiles = explode(',', $post_images);

                ?>
                <div class="row" style="margin-bottom:-10px; ">
                    <div class="col">
                        <div class="card elevation-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-auto" style="margin-top:5px;">
                                        <?php
                                        if ($post['profile_img'] == "") {
                                            echo '<img src="../dist/img/avatar2.png"  class="img-circle elevation-2"  style="width:40px; margin-top:-4px; object-fit: cover;">';
                                        } else {
                                            echo '<img src="../admin/profile_image/' . $post['profile_img'] . '" class="img-circle elevation-2"  style="width:40px; height:40px; margin-top:-4px; object-fit: cover;">';
                                        }
                                        ?>
                                    </div>

                                    <div class="col-8" style="margin-left:-10px;">
                                        <p class="font-weight-bold">
                                            <?php echo htmlspecialchars($post['name']); ?> <span class="font-weight-normal"
                                                style="font-size:14px;"><?php if ($post['location'] != "") {
                                                    echo '<i>is at ' . $post['location'] . '</i>';
                                                } ?></span>
                                        </p>
                                        <h6 style="margin-top:-17px;" class="text-muted">
                                            <?php echo '@' . htmlspecialchars($post['username']); ?>
                                            •
                                            <?php echo $timeDifference; ?>
                                        </h6>
                                    </div>

                                    <div class="col mr-auto text-right">
                                        <div class="dropdown">
                                            <button class="btn btn-white btn-sm border-0 dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <?php if (isset($_SESSION['user'])) {
                                                    if ($_SESSION['user'] == $post['user_id']) {
                                                        echo '<a class="dropdown-item text-left" style="font-size:13px;" href="#" 
                                                        data-toggle="modal" data-target="#editPostModal" 
                                                        data-id="' . htmlspecialchars($post['id']) . '" id="editPostBtn">
                                                        <i class="fas fa-edit"></i> Edit Post
                                                        </a>';
                                                        echo '<a class="dropdown-item text-left delete-post-btn" style="font-size:13px;" data-id="' . htmlspecialchars($post['id']) . '" href="#"><i class="fas fa-trash"></i> Delete</a>';
                                                    } else {
                                                        $post_id = $post['id'];
                                                        echo '<a class="dropdown-item text-center" style="font-size:13px;" href="#" data-id="' . htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8') . '" data-toggle="modal" data-target="#reportPostModal">';
                                                        echo '<i class="fas fa-flag"></i> Report Post</a>';
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row mt-2">
                                    <div class="col">
                                        <div>
                                            <p style="font-size:14px;line-height:15px;">
                                                <?php echo $post['post']; ?>
                                            </p>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <?php if (empty($imageFiles)): ?>
                                        <div class="col-12 text-center">
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($imageFiles as $file): ?>
                                            <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                <div class="d-flex justify-content-center"
                                                    style="height: 0; padding-bottom: 100%; position: relative;">
                                                    <img src="../admin/post_image/<?php echo htmlspecialchars($file); ?>" alt="Image"
                                                        class="img-fluid rounded"
                                                        style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; object-fit: cover;"
                                                        data-toggle="modal" data-target="#imageModal"
                                                        data-src="../admin/post_image/<?php echo htmlspecialchars($file); ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>



                            </div>
                            <div class="card-footer card-outline card-light" style=" margin-top:-30px;">
                                <div class="row">
                                    <div class="col-auto" style="margin-left:-10px;">
                                        <div class="d-inline-flex align-items-start">
                                            <button class="btn btn-light bg-transparent btn-sm border-0 reactionButton"
                                                data-id="<?php echo $post['id']; ?>" data-current-icon="<?php echo $icon_class; ?>">
                                                <!-- Ensure this matches the icon class -->
                                                <i class="<?php echo $icon_class; ?> text-danger" style="font-size:15px;"></i>
                                            </button>
                                            <span class="badge bg-secondary position-relative"
                                                id="reaction-count-<?php echo $post['id']; ?>"
                                                style="font-size:10px;top: -0.5em; margin-left:-25px;"><?php echo $reaction_count; ?></span>
                                        </div>
                                    </div>


                                    <div class="col-auto" style="margin-left:-13px;">
                                        <div class="d-inline-flex align-items-start">
                                            <button class="btn-count btn btn-light bg-transparent btn-sm border-0 comment-section"
                                                data-id="<?php echo $post['id']; ?>">
                                                <i class="far fa-comment-alt text-dark" style="font-size:15px;"></i>
                                            </button>
                                            <span class="badge bg-secondary position-relative"
                                                style="font-size:10px;top: -0.5em; margin-left:-25px;;"><?php echo $comment_count; ?></span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
</div>

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

<!--Add Heart-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reactionButtons = document.querySelectorAll('.reactionButton');

        reactionButtons.forEach(button => {
            button.addEventListener('click', function () {
                const postId = this.getAttribute('data-id');
                const currentIconClass = this.getAttribute('data-current-icon');
                const reactionCountElement = document.getElementById(`reaction-count-${postId}`);
                let reactionCount = parseInt(reactionCountElement.innerText);

                if (currentIconClass === 'far fa-heart') {
                    // Increment the reaction count and change the icon
                    reactionCount++;
                    this.querySelector('i').className = 'fas fa-heart text-danger'; // Change icon to solid
                    this.setAttribute('data-current-icon', 'fas fa-heart'); // Update data attribute
                } else {
                    // Decrement the reaction count and change the icon
                    reactionCount--;
                    this.querySelector('i').className = 'far fa-heart text-danger'; // Change icon to outline
                    this.setAttribute('data-current-icon', 'far fa-heart'); // Update data attribute
                }

                // Update the displayed reaction count
                reactionCountElement.innerText = reactionCount;
            });
        });
    });
</script>

<!--Display, Post and Get Comment-->
<script>
    $(document).ready(function () {
        $('.comment-section').on('click', function () {
            const postId = $(this).data('id');

            // Store the post ID in the modal
            $('#commentModal').data('post-id', postId);

            // Fetch post details
            $.ajax({
                url: 'api/home/fetch-post-comment.php',
                type: 'POST',
                data: { post_id: postId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        const postData = response.data;
                        const imagesHtml = postData.images.map(image => `
                        <img src="${image.trim() ? '../admin/post_image/' + image.trim() : 'https://via.placeholder.com/150'}" 
                             class="img-fluid square-image" alt="Post Image">
                    `).join('');

                        const postHtml = `
                        <div class="post-body">
                            <div class="d-flex align-items-center">
                                <img src="${postData.profile_img ? '../admin/profile_image/' + postData.profile_img : 'https://via.placeholder.com/50'}" 
                                     alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                                <div class="user-info ml-2">
                                    <h6 class="mb-0">${postData.name}</h6>
                                    <small class="text-muted">@${postData.username}</small>
                                </div>
                            </div>
                            <p class="mt-3">${postData.post}</p>
                            <div class="image-album" style="overflow-x: auto; white-space: nowrap;">
                                ${imagesHtml}
                            </div>
                            <hr>
                        </div>
                    `;

                        // Append or replace the content in your post section
                        $('.post-section').html(postHtml);

                        // Now fetch comments
                        fetchComments(postId);
                    } else {
                        alert(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while fetching post details.');
                }
            });
        });

        function fetchComments(postId) {
            console.log("Fetching comments for post ID:", postId); // Log the post ID
            $.ajax({
                url: 'api/home/fetch-comment.php', // Ensure this path is correct
                type: 'POST',
                data: { post_id: postId },
                success: function (data) {
                    if (!data) {
                        $('#post-comment-section').html('<p>No comments available.</p>');
                    } else {
                        $('#post-comment-section').html(data);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching comments: ", error); // Log the error details
                    console.error("AJAX response:", xhr.responseText); // Log the full response
                    $('#post-comment-section').html('<p>Error loading comments. Please try again later.</p>');
                }
            });
        }

        $('#submit-comment').on('click', function () {
            const postId = $('#commentModal').data('post-id'); // Get post ID from the modal's data attribute
            const message = $('#comment-input').val();

            if (message.trim() === "") {
                alert("Please enter a comment.");
                return;
            }

            $.ajax({
                url: 'api/home/add-comment.php',
                type: 'POST',
                data: {
                    post_id: postId,
                    user_id: <?php echo json_encode($_SESSION['user']); ?>, // Ensure user_id is encoded as JSON
                    message: message
                },
                success: function (response) {
                    try {
                        const jsonResponse = JSON.parse(response); // Parse JSON response
                        console.log("Response:", jsonResponse); // Log the response

                        if (jsonResponse.status === 'success') {
                            $('#comment-input').val(''); // Clear the input
                            fetchComments(postId); // Refresh the comments section
                        } else {
                            alert(jsonResponse.message || 'An error occurred.');
                        }
                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        console.log("Raw response:", response); // Log the raw response
                        alert('An error occurred while posting your comment.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error inserting comment:", error);
                    alert('An error occurred while posting your comment.');
                }
            });
        });

    });

</script>


<!-- Edit Post -->
<script>
    $(document).on('click', '#editPostBtn', function () {
        var postId = $(this).data('id');

        $.ajax({
            url: 'api/home/fetch-post.php', // A PHP file to fetch the post data
            type: 'POST',
            data: { post_id: postId },
            dataType: 'json',
            success: function (response) {
                // Log the entire response data
                console.log("Fetched Post Data:", response);

                $('#editPostId').val(response.id);
                $('#editPostText').val(response.post);
                $('#editLocation').val(response.location);
                $('.location-selected').text(response.location);

                // Display the image preview if exists
                if (response.images && response.images.length > 0) {
                    // Log each image fetched
                    console.log("Image Paths:", response.images);
                    $('#imagePreviewContainer').html('');
                    response.images.forEach(function (img) {
                        $('#imagePreviewContainer').append('<img src="' + img + '" class="img-fluid">');
                        $('.image-preview').val(response.images);
                    });
                } else {
                    console.log("No images found for this post.");
                    $('#imagePreviewContainer').html(''); // Clear preview if no images
                }
            },
            error: function (xhr, status, error) {
                // Log any errors that occurred during the AJAX request
                console.error("AJAX Error:", status, error);
            }
        });
    });
</script>

<!--Delete Post-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var postIdToDelete;

        // Open the confirmation modal when delete button is clicked
        document.querySelectorAll('.delete-post-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                postIdToDelete = this.getAttribute('data-id');
                $('#deleteConfirmationModal').modal('show');
            });
        });

        // Handle the confirm delete action
        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            $.ajax({
                url: 'api/home/delete-post.php', // Your PHP delete handler
                type: 'POST',
                data: { post_id: postIdToDelete },
                success: function (response) {
                    if (response === 'success') {
                        $('#deleteConfirmationModal').modal('hide');
                        // Optionally refresh the page or remove the post from the DOM
                        location.reload();
                    } else {
                        alert('Error deleting post.');
                    }
                }
            });
        });
    });

</script>