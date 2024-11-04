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

<?php
include '../inc/config.php';

$userId = isset($_SESSION['user']) ? $_SESSION['user'] : 0;
$travel_preferences = isset($_SESSION['travel_preferences']) ? $_SESSION['travel_preferences'] : '';
$travel_pref_array = is_string($travel_preferences) ? explode(',', $travel_preferences) : $travel_preferences;
$conditions = [];
foreach ($travel_pref_array as $pref) {
    $conditions[] = "FIND_IN_SET('$pref', u.travel_preferences) > 0";
}
$conditions_str = implode(' OR ', $conditions);




// Fetch posts with user information
$pdo_statement = $pdo->prepare("SELECT *, u.id as user_id, p.date_created as date, p.id as id, p.location as location 
                                FROM tbl_post p 
                                LEFT JOIN tbl_user u ON u.id = p.user_id 
                                WHERE $conditions_str 
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

        // Ensure $post_images is an array of image files
        $imageFiles = $post_images ? explode(',', $post_images) : [];

        ?>
        <div class="row" style="margin-bottom:-10px; margin-left:-1px; margin-right:1px;">
            <div class="col">
                <div class="card elevation-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-auto m-auto ">
                                <?php
                                if ($post['profile_img'] == "") {
                                    echo '<a href="profile.php?id=' . $post['user_id'] . '"><img src="../dist/img/avatar2.png"  class="img-circle elevation-2"  style="width:40px; margin-top:-4px;"></a>';
                                } else {
                                    echo '<a href="profile.php?id=' . $post['user_id'] . '"><div><img src="../admin/profile_image/' . $post['profile_img'] . '" class="img-circle elevation-2"  style="width:40px; height:40px; margin-top:-4px;"></div></a>';
                                }
                                ?>
                            </div>

                            <div class="col-8" style="margin-left:-10px;">
                                <p class="font-weight-bold">
                                    <?php echo htmlspecialchars($post['name']); ?>
                                    <?php if ($post['status'] == 1): ?>
                                        <i class="fas fa-check-circle" style="color: #582fff; margin-left: 3px;"
                                            title="Verified"></i>
                                    <?php endif; ?>
                                    <span class="font-weight-normal" style="font-size:14px;">
                                        <?php if (!empty($post['location']) && $post['location'] !== 'null') { // Check if location is not empty and not 'null'
                                                        echo '<i>is at ' . htmlspecialchars($post['location']) . '</i>';
                                                    } ?>
                                    </span>
                                </p>
                                <p style="margin-top:-17px; font-size:13px;" class="text-muted">
                                    <?php echo '@' . htmlspecialchars($post['username']); ?> • <?php echo $timeDifference; ?>
                                </p>
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

                        <div class="row">
                            <div class="col">
                                <div>
                                    <p><?php echo $post['post']; ?></p>

                                </div>
                            </div>
                        </div>

                        <?php if (!empty($imageFiles)): // Only render the row if there are image files ?>
                            <div class="overflow-auto">
                                <div class="d-flex photo-album">
                                    <?php
                                    // Generate a unique ID for this post's Fancybox group
                                    $fancyboxGroup = 'gallery-' . $post['id'];  // Assume $post_id is unique to each post
                        
                                    // Loop through all images
                                    foreach ($imageFiles as $file): ?>
                                        <div class="col-6 col-md-4 col-lg-3 mb-3">
                                            <div class="d-flex justify-content-center"
                                                style="height: 0; padding-bottom: 100%; position: relative;">

                                                <a href="../admin/post_image/<?php echo htmlspecialchars($file); ?>"
                                                    data-fancybox="<?php echo $fancyboxGroup; ?>"
                                                    data-caption="Image for Post <?php echo $post['id']; ?>">
                                                    <img src="../admin/post_image/<?php echo htmlspecialchars($file); ?>" alt="Image"
                                                        class="img-fluid rounded"
                                                        style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; object-fit: cover;">
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>


                        <style>

                        </style>



                    </div>
                    <div class="card-footer card-outline card-light" style=" margin-top:-30px;">
                        <div class="row">
                            <div class="col-auto" style="margin-left:-10px;">
                                <div class="d-inline-flex align-items-start">
                                    <button class="btn-count btn btn-light bg-transparent btn-sm border-0 reactionButton"
                                        data-id="<?php echo $post['id']; ?>" data-current-icon="<?php echo $icon_class; ?>">
                                        <i class="<?php echo $icon_class; ?> text-danger" style="font-size:15px;"></i>
                                    </button>
                                    <span class="badge bg-secondary position-relative"
                                        style="font-size:10px;top: -0.5em; margin-left:-25px;"
                                        id="reaction-count-<?php echo $post['id']; ?>">
                                        <?php echo $reaction_count; ?>
                                    </span>
                                </div>
                            </div>


                            <div class="col-auto" style="margin-left:-13px;">
                                <div class="d-inline-flex align-items-start">
                                    <button class="btn-count btn btn-light bg-transparent btn-sm border-0 comment-section"
                                        data-id="<?php echo $post['id']; ?>">
                                        <i class="far fa-comment-alt text-dark" style="font-size:15px;"></i>
                                    </button>
                                    <span class="badge bg-secondary position-relative"
                                        style="font-size:10px;top: -0.5em; margin-left:-25px;"><?php echo $comment_count; ?></span>
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
?>

<?php
include 'modal/comment.php';
?>


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
                url: 'api/home/add-report.php', // The PHP script to handle the report
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
                        toastr.error(response.message);
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
                        const imagesHtml = postData.images.map((image, index) => `
                            <a href="${image.trim() ? '../admin/post_image/' + image.trim() : 'https://via.placeholder.com/150'}" 
                            data-fancybox="gallery-${postData.id}">
                                <img src="${image.trim() ? '../admin/post_image/' + image.trim() : 'https://via.placeholder.com/150'}" 
                                    class="img-fluid square-image" alt="Post Image">
                            </a>
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