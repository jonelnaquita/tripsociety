<?php
include '../inc/session_user.php';
include 'header.php';
include 'modal/travel-companion.php';

if (isset($_SESSION['user'])) {
    include '../inc/config.php';
    $id = $_SESSION['user'];
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($locations) {
        foreach ($locations as $row) {
            $cover_img = '../admin/cover_image/' . $row['cover_img'];
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


<?php
include 'components/user/user-hero.php'
    ?>


<div class="content-wrapper" style="margin-top: -30px;">
    <section class="content">
        <div class="" style="height:700px;">
            <div class="row mt-2">
                <div class="col">
                    <div class="card bg-transparent shadow-none border-0">
                        <div class="container-fluid mt-2">
                            <?php
                            include 'components/user/user-tab.php';
                            ?>
                            <div class="tab-content" id="myTabContent">

                                <?php
                                include 'components/user/user-post.php';
                                include 'components/user/user-review.php';
                                ?>

                            </div>
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