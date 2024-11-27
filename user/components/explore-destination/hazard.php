<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Hazard Levels</h5>
    </div>
</div>
<h6 class="font-weight-bold">
    <?php
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $location_id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT AVG(hazard_rating) AS avg_rating, COUNT(*) AS review_count FROM tbl_review WHERE location_id = :location_id");
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

<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $location_id = $_GET['id'];

    // Query to get the count of hazard ratings for each level
    $stmt = $pdo->prepare("
        SELECT 
            CASE
                WHEN hazard_rating BETWEEN 0.0 AND 0.49 THEN 'No Hazard'
                WHEN hazard_rating BETWEEN 0.5 AND 1.49 THEN 'Very Low Hazard'
                WHEN hazard_rating BETWEEN 1.5 AND 2.49 THEN 'Low Hazard'
                WHEN hazard_rating BETWEEN 2.5 AND 3.49 THEN 'Moderate Hazard'
                WHEN hazard_rating BETWEEN 3.5 AND 4.49 THEN 'High Hazard'
                WHEN hazard_rating BETWEEN 4.5 AND 5.0 THEN 'Extreme Hazard'
                ELSE 'Unknown'
            END AS hazard_level,
            COUNT(*) AS count
        FROM tbl_review
        WHERE location_id = :location_id
        GROUP BY hazard_level
    ");
    $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
    $stmt->execute();
    $hazards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define hazard labels
    $hazard_labels = [
        'No Hazard' => 'No Hazard',
        'Very Low Hazard' => 'Very Low Hazard',
        'Low Hazard' => 'Low Hazard',
        'Moderate Hazard' => 'Moderate Hazard',
        'High Hazard' => 'High Hazard',
        'Extreme Hazard' => 'Extreme Hazard',
    ];

    // Initialize an array to store hazard counts
    $hazard_counts = array_fill_keys(array_keys($hazard_labels), 0);

    // Populate the counts based on the query results
    foreach ($hazards as $hazard_data) {
        if (array_key_exists($hazard_data['hazard_level'], $hazard_counts)) {
            $hazard_counts[$hazard_data['hazard_level']] = $hazard_data['count'];
        }
    }

    // Get the total number of reviews
    $total_reviews = array_sum($hazard_counts);


    foreach ($hazard_labels as $hazard => $label) {
        $count = $hazard_counts[$hazard];
        $percentage = ($total_reviews > 0) ? ($count / $total_reviews) * 100 : 0;

        echo '<div class="row mb-2">';
        echo '    <div class="col-4">';
        echo '        <p class="mb-1">' . htmlspecialchars($label) . '</p>';
        echo '    </div>';
        echo '    <div class="col-8">';
        echo '        <div class="progress" style="height: 20px; border-radius: 10px;">';
        echo '            <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="' . number_format($percentage, 1) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . number_format($percentage, 1) . '%;">';
        echo '                ' . number_format($percentage, 1) . '%';
        echo '            </div>';
        echo '        </div>';
        echo '    </div>';
        echo '</div>';
    }
}
?>