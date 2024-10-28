<?php
include '../../../inc/config.php';

// Start session and check if the user is logged in
session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Fetch visited locations for outdoor adventure
$query = "
        SELECT l.location_name
        FROM tbl_location l
        INNER JOIN tbl_travel_log t ON l.id = t.location_id
        WHERE t.user_id = :user_id
    ";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$locations = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Check if each location has been visited
$mt_batulao = in_array('Mt. Batulao', $locations) ? 1 : 0;
$mt_maculot = in_array('Mt. Maculot', $locations) ? 1 : 0;
$mt_talumpok = in_array('Mt. Talumpok', $locations) ? 1 : 0;
$malagaslas_falls = in_array('Malagaslas Falls', haystack: $locations) ? 1 : 0;

// Calculate progress
$total_adventures = 4; // Number of locations for outdoor adventure
$visited_adventures = $mt_batulao + $mt_maculot + $mt_talumpok + $malagaslas_falls;
$progress_percentage = ($visited_adventures / $total_adventures) * 100;

// Return the result as JSON
echo json_encode([
    'mt_batulao' => $mt_batulao,
    'mt_maculot' => $mt_maculot,
    'mt_talumpok' => $mt_talumpok,
    'malagaslas_falls' => $malagaslas_falls,
    'progress_percentage' => $progress_percentage
]);
