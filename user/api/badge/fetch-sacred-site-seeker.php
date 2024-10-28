<?php
include '../../../inc/config.php';

// Start session and check if the user is logged in
session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Fetch visited locations for Sacred Site Seeker
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
$marian_orchard = in_array('Marian Orchard', $locations) ? 1 : 0;
$monte_maria = in_array('Monte Maria', $locations) ? 1 : 0;
$caleruega_retreat_house = in_array('Caleruega Retreat House', $locations) ? 1 : 0;
$kabanal_banalang_puso = in_array('Kabanal-Banalang Puso ni Hesus', $locations) ? 1 : 0;
$basilica_of_st_martin = in_array('Basilica of St. Martin De Tours', $locations) ? 1 : 0;

// Calculate progress
$total_sites = 5; // Number of sacred site locations
$visited_sites = $marian_orchard + $monte_maria + $caleruega_retreat_house + $kabanal_banalang_puso + $basilica_of_st_martin;
$progress_percentage = ($visited_sites / $total_sites) * 100;

// Return the result as JSON
echo json_encode([
    'marian_orchard' => $marian_orchard,
    'monte_maria' => $monte_maria,
    'caleruega_retreat_house' => $caleruega_retreat_house,
    'kabanal_banalang_puso' => $kabanal_banalang_puso,
    'basilica_of_st_martin' => $basilica_of_st_martin,
    'progress_percentage' => $progress_percentage
]);
