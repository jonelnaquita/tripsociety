<?php
include '../../../inc/config.php';

// Start the session to access user data
session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

try {
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

    // Check if badge already exists for the user
    $badge_check_sql = "SELECT COUNT(*) FROM tbl_badge_accomplishment WHERE user_id = :user_id AND badge = :badge";
    $badge_check_stmt = $pdo->prepare($badge_check_sql);
    $badge_check_stmt->execute([
        'user_id' => $user_id,
        'badge' => 'Relaxation Spot'
    ]);
    $badge_exists = $badge_check_stmt->fetchColumn() > 0;

    // Insert the badge accomplishment if progress is 100% and badge does not exist
    if ($progress_percentage == 100 && !$badge_exists) {
        $badge_sql = "INSERT INTO tbl_badge_accomplishment (user_id, badge, color, icon, date_created) 
                      VALUES (:user_id, :badge, :color, :icon, :date_created)";
        $badge_stmt = $pdo->prepare($badge_sql);
        $badge_stmt->execute([
            'user_id' => $user_id,
            'badge' => 'Relaxation Spot',
            'color' => '#F6D55C',
            'icon' => 'fa-hot-tub',
            'date_created' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
        ]);
    }

    // Return the result as JSON
    echo json_encode([
        'batangas_lakelands' => $batangas_lakelands,
        'canyon_cove_hotel_and_spa' => $canyon_cove_hotel_and_spa,
        'camp_laiya_resort' => $camp_laiya_resort,
        'progress_percentage' => $progress_percentage
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>