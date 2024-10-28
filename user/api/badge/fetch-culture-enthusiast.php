<?php
include '../../../inc/config.php';

session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

try {
    // Query to join tbl_location and tbl_travel_log based on location_id, filtering by user_id
    $query = "
        SELECT l.location_name
        FROM tbl_location l
        INNER JOIN tbl_travel_log t ON l.id = t.location_id
        WHERE t.user_id = :user_id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $locations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Check if specific locations are in the result
    $museo_miguel_malvar = in_array('Museo ni Miguel Malvar', $locations) ? 1 : 0;
    $apolinario_mabini_museum = in_array('Apolinario Mabini Museum', $locations) ? 1 : 0;

    // Calculate progress
    $total_museums = 2;
    $visited_museums = $museo_miguel_malvar + $apolinario_mabini_museum;
    $progress_percentage = ($visited_museums / $total_museums) * 100;

    // Return the result as JSON
    echo json_encode([
        'museo_miguel_malvar' => $museo_miguel_malvar,
        'apolinario_mabini_museum' => $apolinario_mabini_museum,
        'progress_percentage' => $progress_percentage
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
