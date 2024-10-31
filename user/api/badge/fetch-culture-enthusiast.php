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

    // Check if badge already exists for the user
    $badge_check_sql = "SELECT COUNT(*) FROM tbl_badge_accomplishment WHERE user_id = :user_id AND badge = :badge";
    $badge_check_stmt = $pdo->prepare($badge_check_sql);
    $badge_check_stmt->execute([
        'user_id' => $user_id,
        'badge' => 'Culture Enthusiast'
    ]);
    $badge_exists = $badge_check_stmt->fetchColumn() > 0;

    // Insert the badge accomplishment if progress is 100% and badge does not exist
    if ($progress_percentage == 100 && !$badge_exists) {
        $badge_sql = "INSERT INTO tbl_badge_accomplishment (user_id, badge, color, icon, date_created) 
                      VALUES (:user_id, :badge, :color, :icon, :date_created)";
        $badge_stmt = $pdo->prepare($badge_sql);
        $badge_stmt->execute([
            'user_id' => $user_id,
            'badge' => 'Culture Enthusiast',
            'color' => '#20639B',
            'icon' => 'fa-passport',
            'date_created' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
        ]);
    }

    // Return the result as JSON
    echo json_encode([
        'museo_miguel_malvar' => $museo_miguel_malvar,
        'apolinario_mabini_museum' => $apolinario_mabini_museum,
        'progress_percentage' => $progress_percentage
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>