<hr>

<style>
    .rating-container {
        display: flex;
        align-items: center;
    }

    .star-rating i {
        color: gold;
        margin-right: 2px;
    }

    .star-rating .far {
        color: lightgray;
    }

    .rating-info {
        margin-left: 10px;
    }
</style>
<div class="row">
    <div class="col">
        <h5 class="font-weight-bold">Reviews</h5>
        <h6 class="font-weight-bold">


            <?php
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $location_id = $_GET['id'];
                $stmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM tbl_review WHERE location_id = :location_id");
                $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $avg_rating = $result['avg_rating'];
                    $review_count = $result['review_count'];
                    $full_stars = floor($avg_rating);
                    $half_star = ($avg_rating - $full_stars >= 0.5) ? 1 : 0;
                    $empty_stars = 5 - ($full_stars + $half_star);
                    echo '<div class="rating-container">';
                    echo '<span class="mr-2">' . number_format($avg_rating, 1) . ' / 5</span>';
                    echo '<div class="star-rating">';
                    for ($i = 0; $i < $full_stars; $i++) {
                        echo '<i class="fas fa-star"></i>';
                    }
                    if ($half_star) {
                        echo '<i class="fas fa-star-half-alt"></i>';
                    }
                    for ($i = 0; $i < $empty_stars; $i++) {
                        echo '<i class="far fa-star"></i>';
                    }
                    echo '</div>';
                    echo '<span class="rating-info text-muted ml-2">' . $review_count . ' Reviews</span>';
                    echo '</div>';
                } else {
                    echo 'No reviews found for the given location ID.';
                }
            } else {
                echo 'Invalid ID parameter.';
            }
            ?>
        </h6>
    </div>

</div>




<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $location_id = $_GET['id'];

    // Query to get the count of each rating
    $stmt = $pdo->prepare("
            SELECT rating, COUNT(*) AS count
            FROM tbl_review
            WHERE location_id = :location_id
            GROUP BY rating
        ");
    $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
    $stmt->execute();
    $ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total reviews count
    $total_reviews = array_sum(array_column($ratings, 'count'));

    // Define rating labels
    $rating_labels = [
        5 => 'Excellent',
        4 => 'Very Good',
        3 => 'Good',
        2 => 'Average',
        1 => 'Poor'
    ];

    // Display progress bars for each rating category
    foreach ($rating_labels as $rating => $label) {
        $count = 0;
        foreach ($ratings as $rating_data) {
            if ($rating_data['rating'] == $rating) {
                $count = $rating_data['count'];
                break;
            }
        }
        $percentage = ($total_reviews > 0) ? ($count / $total_reviews) * 100 : 0;
        echo '<div class="row">';
        echo '<div class="col-4">';
        echo '<p class="rating-label">' . $label . '</p>';
        echo '</div>';
        echo '<div class="col-8 mt-1" style="margin-left:-20px;">';
        echo '<div class="progress" style="height:30px; border-radius:20px; height:20px;">';
        echo '<div class="progress-bar bg-primary" role="progressbar" aria-valuenow="' . number_format($percentage, 1) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . number_format($percentage, 1) . '%;">';
        echo number_format($percentage, 1) . '%';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
?>