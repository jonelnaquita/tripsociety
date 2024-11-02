<?php
include '../inc/session_user.php';
include 'header.php';
include 'modal/comment.php';
include 'modal/home.php';
include 'modal/report.php';

if (isset($_GET['id'])) {
    include '../inc/config.php';
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($locations) {
        foreach ($locations as $row) {
            if ($row['cover_img'] == "") {
                $cover_img = '../dist/img/image.jpg';
            } else {
                $cover_img = '../admin/cover_image/' . $row['cover_img'];
            }
            $profile_img = '../admin/profile_image/' . $row['profile_img'];
        }
    }


    // Count reviews
    $stmt_reviews = $pdo->prepare("SELECT COUNT(*) as review_count FROM tbl_review WHERE user_id = :id");
    $stmt_reviews->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_reviews->execute();
    $review_count = $stmt_reviews->fetchColumn();

    // Count posts
    $stmt_posts = $pdo->prepare("SELECT COUNT(*) as post_count FROM tbl_post WHERE user_id = :id");
    $stmt_posts->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_posts->execute();
    $post_count = $stmt_posts->fetchColumn();


}

?>

<link rel="stylesheet" href="assets/css/post.css">

<style>
    /* Additional custom styles for background image */
    .card-background {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>

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

    .badge-accomplishment {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        color: #fff;
        font-size: 10px;
        font-weight: 500;
        border-radius: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
        cursor: default;
    }

    .badge-accomplishment:hover {
        transform: translateY(-2px);
    }

    .badge-text {
        margin: 0;
    }
</style>

<div class="card card-background rounded-0" style="background-image: url('<?php echo $cover_img; ?>');">
    <div class="card-body text-center text-white">
        <div class="mt-3">
            <?php
            if ($row['profile_img'] == "") {
                echo '<img src="../dist/img/avatar2.png" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">';
            } else {
                echo '<img src="' . $profile_img . '" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">';
            }
            ?>
        </div>

        <h6 class="font-weight-bold mt-2"><?php echo $row['name']; ?></h6>
        <p class="mb-0" style="font-size:12px; margin-top:-10px;">@<?php echo $row['username']; ?></p>

        <div id="badge-accomplishment"></div>

        <div class="row w-50 m-auto">
            <div class="col text-center">
                <p class="text-white">Posts</p>
                <h6 class="font-weight-bold text-white" style="margin-top:-15px;"><?php echo $post_count; ?>
                </h6>
            </div>
            <div class="col text-center">
                <p class="text-white">Reviews</p>
                <h6 class="font-weight-bold text-white" style="margin-top:-15px;"><?php echo $review_count; ?>
                </h6>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Get the user_id from the URL if present, otherwise it will default to session in PHP
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('id');

        $.ajax({
            url: 'api/badge/fetch-badge-accomplishment.php', // PHP script to fetch badges
            method: 'GET',
            data: { user_id: userId }, // Send user_id if available
            dataType: 'json',
            success: function (data) {
                // Clear the badge container
                $('#badge-accomplishment').empty();

                // Check for any error in response
                if (data.error) {
                    $('#badge-accomplishment').html('<p class="error">' + data.error + '</p>');
                    return;
                }

                // Iterate over badges and append them to #badge-accomplishment
                data.forEach(function (badge) {
                    const badgeElement = `
                    <div class="badge badge-accomplishment" style="background-color: ${badge.color};">
                        <i class="fas ${badge.icon}"></i>
                        <span class="ml-1 badge-text">${badge.badge}</span>
                    </div>
                `;
                    $('#badge-accomplishment').append(badgeElement);
                });
            },
            error: function (xhr, status, error) {
                $('#badge-accomplishment').html('<p class="error">An error occurred. Please try again later.</p>');
            }
        });
    });
</script>


<div class="content-wrapper" style="margin-top: -30px;">
    <div class="mt-2">
        <div class="col">
            <div class="card bg-transparent shadow-none border-0">
                <div class="container-fluid mt-2">
                    <ul class="nav nav-tabs bg-white p-2" style="border-radius:5px;" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active" role="tab"
                                aria-controls="active" aria-selected="true">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" id="link2-tab" data-toggle="tab" href="#link2" role="tab"
                                aria-controls="link2" aria-selected="false">Reviews</a>
                        </li>
                        <li class="nav-item ml-auto mr-3">
                            <?php
                            if ($_GET['id'] != $_SESSION['user']) {
                                $stmt = $pdo->prepare("
                                            SELECT status
                                            FROM tbl_travel_companion 
                                            WHERE user_id = :sessionId 
                                            AND companion_id = :getId
                                        ");

                                $stmt->execute(['sessionId' => $_SESSION['user'], 'getId' => $_GET['id']]);

                                // Fetch the status
                                $status = $stmt->fetchColumn();

                                // Check if a status was found
                                if ($status !== false) {
                                    if ($status == 'Requesting') {
                                        echo '<div class="font-weight-bold alert alert-warning pl-2 pr-2 pt-1 pb-1 mt-2" style="font-size:10px; margin-bottom:-5px;">Invited</div>';
                                    } elseif ($status == 'Cancelled') {
                                        echo '<div class="font-weight-bold alert alert-secondary pl-2 pr-2 pt-1 pb-1 mt-2" style="font-size:10px; margin-bottom:-5px;">Cancelled</div>';
                                    } else {
                                        echo '<div class="font-weight-bold alert alert-primary pl-2 pr-2 pt-1 pb-1 mt-2" style="font-size:10px; margin-bottom:-5px;"><i class="fas fa-user-check"></i> Travel Companion</div>';
                                    }
                                } else {
                                    ?>
                                    <?php
                                    // Prepare the SQL statement
                                    $stmt = $pdo->prepare("
                                                SELECT companion_id, user_id, status
                                                FROM tbl_travel_companion 
                                                WHERE companion_id = :sessionId AND user_id = :getId
                                            ");

                                    // Execute the query with bound parameters
                                    $stmt->execute(['sessionId' => $_SESSION['user'], 'getId' => $_GET['id']]);

                                    // Fetch the result
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                    // Check if a matching record was found
                                    if ($result && $result['user_id'] == $_GET['id'] && $result['companion_id'] == $_SESSION['user']) {
                                        // If the request status is 'Accepted', display the badge
                                        if ($result['status'] == 'Accepted') {
                                            ?>
                                            <div class="font-weight-bold alert alert-primary pl-2 pr-2 pt-1 pb-1 mt-2"
                                                style="font-size:10px; margin-bottom:-5px;">
                                                <i class="fas fa-user-check"></i> Travel Companion
                                            </div>
                                            <?php
                                        } elseif ($result['status'] == 'Cancelled') {
                                            ?>
                                            <div class="font-weight-bold alert alert-secondary pl-2 pr-2 pt-1 pb-1 mt-2"
                                                style="font-size:10px; margin-bottom:-5px;">
                                                Declined
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <form action="../inc/function.php" method="POST">
                                                <input type="hidden" value="<?php echo htmlspecialchars($result['user_id']); ?>"
                                                    name="user_id">
                                                <input type="hidden" value="<?php echo htmlspecialchars($result['companion_id']); ?>"
                                                    name="companion_id">
                                                <button type="submit" name="accept_invite"
                                                    class="btn btn-warning btn-sm font-weight-bold mt-1" style="font-size:12px;">
                                                    Accept Invite
                                                </button>
                                            </form>
                                            <?php
                                        }
                                    } else {
                                        // If no matching record or not a travel companion, show the travel companion icon link
                                        ?>
                                        <a class="nav-link text-dark p-0 mt-2" data-toggle="modal"
                                            data-id="<?php echo htmlspecialchars($_GET['id']); ?>" data-target="#disclaimerModal">
                                            <div class="border-dark pt-1 pb-1"
                                                style="border-radius:20px; margin-top:-5px; padding-left:5px; padding-right:5px; border:1px solid black;">
                                                <img src="../img/companion.png" style="width:20px;">
                                            </div>
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            ?>

                        <li class="nav-item">
                            <?php
                            if ($_GET['id'] != $_SESSION['user']) {
                                ?>
                                <a class="nav-link text-dark p-0 mt-3 mr-2"
                                    href="messages2.php?id=<?php echo $_GET['id']; ?>">
                                    <div class="d-flex align-items-center position-relative" style="margin-top: -7px;">
                                        <i class="material-icons text-dark" style="font-size: 28px;">chat_bubble_outline</i>
                                    </div>
                                </a>
                                <?php
                            }
                            ?>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <label class="font-weight-bold mt-2">Details</label>

                            <div class="input-group" style="margin-top:-10px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-0"><i
                                            class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input class="form-control form-control-border bg-transparent"
                                    value="<?php echo !empty($row['location']) ? $row['location'] : 'No City Selected'; ?>"
                                    readonly>
                            </div>

                            <br>
                            <?php
                            if (isset($_GET['id'])) {
                                include '../inc/config.php';
                                // session_start();
                                $userId = $_GET['id']; // Assuming user ID is stored in session
                            
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
                                        // Check if the user has reacted to this post
                                        $reaction_statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_reaction WHERE user_id = :user_id AND post_id = :post_id");
                                        $reaction_statement->bindParam(':user_id', $_SESSION['user'], PDO::PARAM_INT); // Use session user ID for checking reactions
                                        $reaction_statement->bindParam(':post_id', $post['id'], PDO::PARAM_INT);
                                        $reaction_statement->execute();

                                        // Fetch the count of reactions
                                        $reaction_count = $reaction_statement->fetchColumn();
                                        $has_reacted = ($reaction_count > 0); // Boolean value indicating if the user has reacted
                            
                                        // Determine the icon class based on the reaction status
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
                                        <div class="row" style="margin-bottom:-10px;">
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
                                                                        <i class="fas fa-check-circle"
                                                                            style="color: #582fff; margin-left: 3px;"
                                                                            title="Verified"></i>
                                                                    <?php endif; ?>
                                                                    <span class="font-weight-normal" style="font-size:14px;">
                                                                        <?php if (!empty($post['location']) && $post['location'] !== 'null') { // Check if location is not empty and not 'null'
                                                                                            echo '<i>is at ' . htmlspecialchars($post['location']) . '</i>';
                                                                                        } ?>
                                                                    </span>
                                                                </p>
                                                                <p style="margin-top:-17px; font-size:13px;" class="text-muted">
                                                                    <?php echo '@' . htmlspecialchars($post['username']); ?>
                                                                    • <?php echo $timeDifference; ?>
                                                                </p>
                                                            </div>




                                                            <div class="col mr-auto text-right">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-white btn-sm border-0 dropdown-toggle"
                                                                        type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                                        aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fas fa-ellipsis-h"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu dropdown-menu-right"
                                                                        aria-labelledby="dropdownMenuButton">
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
                                                                                echo '<a class="dropdown-item text-center" style="font-size:13px;" data-id="' . htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8') . '" data-toggle="modal" data-target="#reportPostModal">';
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

                                                        <?php if (!empty($post_images)): // Only render the row if there are image files ?>
                                                            <div class="row">
                                                                <?php foreach ($imageFiles as $file): ?>
                                                                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                                                                        <div class="d-flex justify-content-center"
                                                                            style="height: 0; padding-bottom: 100%; position: relative;">
                                                                            <img src="../admin/post_image/<?php echo htmlspecialchars($file); ?>"
                                                                                alt="Image" class="img-fluid rounded"
                                                                                style="position: absolute; top: 0; left: 0; height: 100%; width: 100%; object-fit: cover;"
                                                                                data-toggle="modal" data-target="#imageModal"
                                                                                data-src="../admin/post_image/<?php echo htmlspecialchars($file); ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <!-- Nothing will be displayed if there are no images -->
                                                        <?php endif; ?>




                                                    </div>
                                                    <div class="card-footer card-outline card-light" style=" margin-top:-30px;">
                                                        <div class="row">
                                                            <div class="col-auto" style="margin-left:-10px;">
                                                                <div class="d-inline-flex align-items-start">
                                                                    <button
                                                                        class="btn-count btn btn-light bg-transparent btn-sm border-0 reactionButton"
                                                                        data-id="<?php echo $post['id']; ?>"
                                                                        data-current-icon="<?php echo $icon_class; ?>">
                                                                        <i class="<?php echo $icon_class; ?> text-danger"
                                                                            style="font-size:15px;"></i>
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
                                                                    <button
                                                                        class="btn-count btn btn-light bg-transparent btn-sm border-0 comment-section"
                                                                        data-id="<?php echo $post['id']; ?>">
                                                                        <i class="far fa-comment-alt text-dark"
                                                                            style="font-size:15px;"></i>
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

                            }
                            ?>
                        </div>



                        <?php
                        include 'components/profile/review-tab.php';
                        ?>
                        <br><br>
                        <br><br>


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
        // When the modal is shown
        $('#disclaimerModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var companionId = button.data('id'); // Extract info from data-* attributes

            // Update the modal's content
            var modal = $(this);
            modal.find('#companionId').val(companionId); // Set the value of the input field
        });
    });
</script>


<!-- uPDATE sTATUS Modal -->
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
                <form action="../inc/function.php" method="POST">
                    <!-- Input field to show the ID -->
                    <div class="form-group">
                        <input type="text" name="companion_id" hidden class="form-control" id="companionId" readonly>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" type="submit" name="add_travel_companion"
                            class="btn btn-primary">Proceed</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
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
                        alert(response.message);
                        $('#reportPostModal').modal('hide'); // Hide the modal
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            alert('Please select a violation reason.');
        }
    });
</script>