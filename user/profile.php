<?php
include 'header.php';

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
<style>
    /* Additional custom styles for background image */
    .card-background {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>



<div class="row mt-2">
    <div class="col">
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
    </div>
</div>
<div class="content-wrapper" style="margin-top:-50px;">
    <section class="content">
        <div class="" style="height:700px;">
            <div class="row mt-2">
                <div class="col">
                    <div class="card bg-transparent shadow-none border-0">
                        <div class="container-fluid mt-2">
                            <ul class="nav nav-tabs bg-white p-2" style="border-radius:5px;" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="active-tab" data-toggle="tab" href="#active"
                                        role="tab" aria-controls="active" aria-selected="true">Posts</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="link2-tab" data-toggle="tab" href="#link2"
                                        role="tab" aria-controls="link2" aria-selected="false">Reviews</a>
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
                                                        <input type="hidden"
                                                            value="<?php echo htmlspecialchars($result['companion_id']); ?>"
                                                            name="companion_id">
                                                        <button type="submit" name="accept_invite"
                                                            class="btn btn-warning btn-sm font-weight-bold mt-1"
                                                            style="font-size:12px;">
                                                            Accept Invite
                                                        </button>
                                                    </form>
                                                    <?php
                                                }
                                            } else {
                                                // If no matching record or not a travel companion, show the travel companion icon link
                                                ?>
                                                <a class="nav-link text-dark p-0 mt-2" data-toggle="modal"
                                                    data-id="<?php echo htmlspecialchars($_GET['id']); ?>"
                                                    data-target="#disclaimerModal">
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
                                            <div class="d-flex align-items-center position-relative"
                                                style="margin-top: -7px;">
                                                <i class="material-icons text-dark"
                                                    style="font-size: 28px;">chat_bubble_outline</i>
                                            </div>
                                        </a>
                                        <?php
                                    }
                                    ?>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="active" role="tabpanel"
                                    aria-labelledby="active-tab">
                                    <label class="font-weight-bold mt-2">Details</label>

                                    <div class="input-group" style="margin-top:-10px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent border-0"><i
                                                    class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input class="form-control form-control-border bg-transparent"
                                            value="<?php echo $row['location']; ?>">
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
                                                $datePosted = new DateTime($date);
                                                $now = new DateTime();

                                                $interval = $datePosted->diff($now);
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
                                                                                <i class="fas fa-check-circle"
                                                                                    style="color: #582fff; margin-left: 3px;"
                                                                                    title="Verified"></i>
                                                                            <?php endif; ?>
                                                                            <span class="font-weight-normal"
                                                                                style="font-size:14px;">
                                                                                <?php if ($post['location'] != "") {
                                                                                    echo '<i>is at ' . $post['location'] . '</i>';
                                                                                } ?>
                                                                            </span>
                                                                        </p>
                                                                        <h6 style="margin-top:-17px;" class="text-dark">
                                                                            <?php echo '@' . htmlspecialchars($post['username']); ?>
                                                                            • <?php echo $timeDifference; ?>
                                                                        </h6>
                                                                    </div>


                                                                    <div class="col mr-auto text-right">
                                                                        <div class="dropdown">
                                                                            <button
                                                                                class="btn btn-white btn-sm border-0 dropdown-toggle"
                                                                                type="button" id="dropdownMenuButton"
                                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                                aria-expanded="false">
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
                                                                                        echo '<a class="dropdown-item text-left delete-post-btn" data-id="' . htmlspecialchars($post['id']) . '" href="#"><i class="fas fa-trash"></i> Delete</a>';
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

                                                                <div class="row">
                                                                    <?php if (empty($imageFiles)): ?>
                                                                        <div class="col-12 text-center">
                                                                        </div>
                                                                    <?php else: ?>
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
                                                                    <?php endif; ?>
                                                                </div>


                                                                <!-- Modal -->
                                                                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog"
                                                                    aria-labelledby="imageModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                        <div class="modal-content border-0 bg-transparent">
                                                                            <div class="position-relative">
                                                                                <img src="" id="modalImage" class="img-fluid"
                                                                                    alt="Large Image">
                                                                                <button type="button"
                                                                                    class="close position-absolute"
                                                                                    style="top: 10px; right: 10px;"
                                                                                    data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <script>
                                                                    $(document).ready(function () {
                                                                        // When an image is clicked, set the src of the modal image
                                                                        $('[data-toggle="modal"]').click(function () {
                                                                            var imageSrc = $(this).data('src');
                                                                            $('#modalImage').attr('src', imageSrc);
                                                                        });
                                                                    });
                                                                </script>


                                                            </div>
                                                            <div class="card-footer card-outline card-light"
                                                                style=" margin-top:-30px;">
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
                                                                                style="font-size:10px;top: -0.5em; margin-left:-15px;"
                                                                                id="reaction-count-<?php echo $post['id']; ?>">
                                                                                <?php echo $reaction_count; ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-auto" style="margin-left:-13px;">
                                                                        <div class="d-inline-flex align-items-start">
                                                                            <button
                                                                                class="btn-count btn btn-light bg-transparent btn-sm border-0 addComment"
                                                                                data-id="<?php echo $post['id']; ?>">
                                                                                <i class="far fa-comment-alt text-dark"
                                                                                    style="font-size:15px;"></i>
                                                                            </button>
                                                                            <span class="badge bg-secondary position-relative"
                                                                                style="font-size:10px;top: -0.5em; margin-left:-15px;"><?php echo $comment_count; ?></span>
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



                                <div class="tab-pane fade" id="link2" role="tabpanel" aria-labelledby="link2-tab">
                                    <label class="font-weight-bold mt-2">Details</label>

                                    <div class="input-group" style="margin-top:-10px;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent border-0"><i
                                                    class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input class="form-control form-control-border bg-transparent"
                                            value="<?php echo $row['location']; ?>">
                                    </div>
                                    <br>

                                    <?php
                                    if (isset($_GET['id'])) {
                                        include '../inc/config.php';
                                        $userId = $_GET['id']; // Assuming user ID is stored in session
                                    
                                        // Fetch posts with user information
                                        $pdo_statement = $pdo->prepare("SELECT *, p.date_created as date, p.id as id, tl.location as location 
                                FROM tbl_review p 
                                LEFT JOIN tbl_user u ON u.id = p.user_id 
                                LEFT JOIN tbl_location tl ON tl.id = p.location_id
                                where p.user_id = " . $userId . "
                                ORDER BY p.id DESC");
                                        $pdo_statement->execute();
                                        $posts = $pdo_statement->fetchAll();

                                        if (!empty($posts)) {


                                            foreach ($posts as $post) {
                                                $date = $post['date'];
                                                $datePosted = new DateTime($date);
                                                $now = new DateTime();

                                                $interval = $datePosted->diff($now);
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


                                                $dateTimeString = $date; // Assume $date contains the date and time
                                                $dateTime = new DateTime($dateTimeString);
                                                $formattedDate = $dateTime->format('d/m/Y');

                                                $image_statement = $pdo->prepare("SELECT images FROM tbl_review WHERE id = ?");
                                                $image_statement->execute([$post['id']]);
                                                $post_images = $image_statement->fetchColumn();
                                                $imageFiles = explode(',', $post_images);
                                                $city = $post['city'];
                                                $maxLength = 13;
                                                if (strlen($city) > $maxLength) {
                                                    $city = substr($city, 0, $maxLength) . '...';
                                                }

                                                ?>
                                                <div class="row" style="margin-bottom:-10px; ">
                                                    <div class="col">
                                                        <div class="card elevation-2">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-auto" style="margin-top:5px;">
                                                                        <?php
                                                                        if ($post['profile_img'] == "") {
                                                                            echo '<img src="../dist/img/avatar2.png"  class="img-circle elevation-2"  style="width:40px; margin-top:-4px;">';
                                                                        } else {
                                                                            echo '<img src="../admin/profile_image/' . $post['profile_img'] . '" class="img-circle elevation-2"  style="width:40px; margin-top:-4px;">';
                                                                        }
                                                                        ?>
                                                                    </div>

                                                                    <div class="col-8" style="margin-left:-10px;">
                                                                        <p class="font-weight-bold">
                                                                            <?php echo htmlspecialchars($post['name']); ?>
                                                                        </p>
                                                                        <h6 style="margin-top:-17px; font-size:13px"
                                                                            class="text-dark"><i
                                                                                class="fas fa-map-marker-alt mr-1"></i><?php echo $post['location_name']; ?>
                                                                            • <?php echo $city; ?></h6>
                                                                    </div>


                                                                </div>

                                                                <div class="row mt-2">
                                                                    <div class="col">
                                                                        <div>
                                                                            <p style="font-size:14px;line-height:15px;">
                                                                                <?php echo $post['review']; ?>
                                                                            </p>

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <?php foreach ($imageFiles as $file): ?>
                                                                        <div class="col-auto mb-3 m-0">
                                                                            <div class="d-flex justify-content-left"
                                                                                style="height:70px;">
                                                                                <img src="../admin/review_image/<?php echo htmlspecialchars($file); ?>"
                                                                                    alt="Image" class="img-fluid"
                                                                                    style="max-height: 100%; width: auto; object-fit: cover;"
                                                                                    data-toggle="modal" data-target="#imageModal"
                                                                                    data-src="../admin/post_image/<?php echo htmlspecialchars($file); ?>">
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>



                                                            </div>
                                                            <div class="card-footer card-outline card-light"
                                                                style=" margin-top:-30px;">
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <p class="text-muted" style="font-size:13px;">written on
                                                                            <?php echo $formattedDate; ?>
                                                                        </p>
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
                                <br><br>
                                <br><br>


                            </div>
                        </div>


    </section>
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