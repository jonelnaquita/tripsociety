<div class="row mt-3">
    <div class="col">
        <h5 class="font-weight-bold">Hazard</h5>
        <h6 class="font-weight-bold">
        </h6>
    </div>
</div>

<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $location_id = $_GET['id'];

    // Query to get the count of each hazard level
    $stmt = $pdo->prepare("
        SELECT hazard, COUNT(*) AS count
        FROM tbl_review
        WHERE location_id = :location_id
        GROUP BY hazard
    ");
    $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
    $stmt->execute();
    $hazards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Define hazard labels
    $hazard_labels = [
        'No Hazard' => 'No Hazard',
        'Very low hazard' => 'Very Low Hazard',
        'Low hazard' => 'Low Hazard',
        'Moderate hazard' => 'Moderate Hazard',
        'High hazard' => 'High Hazard',
        'Extreme hazard' => 'Extreme Hazard'
    ];

    // Initialize an array to store hazard counts
    $hazard_counts = array_fill_keys(array_keys($hazard_labels), 0);

    // Populate the counts based on the query results
    foreach ($hazards as $hazard_data) {
        if (array_key_exists($hazard_data['hazard'], $hazard_counts)) {
            $hazard_counts[$hazard_data['hazard']] = $hazard_data['count'];
        }
    }

    // Get total reviews count
    $total_reviews = array_sum($hazard_counts);

    // Display progress bars for each hazard category
    foreach ($hazard_labels as $hazard => $label) {
        $count = $hazard_counts[$hazard];
        $percentage = ($total_reviews > 0) ? ($count / $total_reviews) * 100 : 0;

        echo '<div class="row">'; // Added mb-2 for margin-bottom between rows
        echo '<div class="col-4">'; // Adjusted column size to better fit the content
        echo '<p class="hazard-label">' . htmlspecialchars($label) . '</p>'; // Ensure safe output
        echo '</div>';
        echo '<div class="col-8" style="margin-left:-20px;">';
        echo '<div class="progress" style="height: 20px; border-radius: 20px;">';
        echo '<div class="progress-bar bg-primary" role="progressbar" aria-valuenow="' . number_format($percentage, 1) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . number_format($percentage, 1) . '%;">';
        echo number_format($percentage, 1) . '%';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
?>