<?php
include '../../../inc/config.php';

// Start the session to access user data
session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Query to fetch the locations visited by the user
$query = "
        SELECT l.location_name
        FROM tbl_location l
        INNER JOIN tbl_travel_log t ON l.id = t.location_id
        WHERE t.user_id = :user_id
    ";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$locations = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Check if the user has visited each of the specific locations
$batangas_lakelands = in_array('Batangas Lakelands', $locations) ? 1 : 0;
$canyon_cove_hotel_and_spa = in_array('Canyon Cove Hotel and Spa', $locations) ? 1 : 0;
$camp_laiya_resort = in_array('Camp Laiya Resort', $locations) ? 1 : 0;

// Calculate progress
$total_locations = 3; // Total number of locations in this category
$visited_locations = $batangas_lakelands + $canyon_cove_hotel_and_spa + $camp_laiya_resort;
$progress_percentage = ($visited_locations / $total_locations) * 100;

// Return the result as JSON
echo json_encode([
    'batangas_lakelands' => $batangas_lakelands,
    'canyon_cove_hotel_and_spa' => $canyon_cove_hotel_and_spa,
    'camp_laiya_resort' => $camp_laiya_resort,
    'progress_percentage' => $progress_percentage
]);
