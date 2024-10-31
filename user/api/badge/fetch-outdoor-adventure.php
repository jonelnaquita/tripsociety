<?php
include '../../../inc/config.php';

// Start session and check if the user is logged in
session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

try {
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
    $malagaslas_falls = in_array('Malagaslas Falls', $locations) ? 1 : 0;

    // Calculate progress
    $total_adventures = 4; // Number of locations for outdoor adventure
    $visited_adventures = $mt_batulao + $mt_maculot + $mt_talumpok + $malagaslas_falls;
    $progress_percentage = ($visited_adventures / $total_adventures) * 100;

    // Check if badge already exists for the user
    $badge_check_sql = "SELECT COUNT(*) FROM tbl_badge_accomplishment WHERE user_id = :user_id AND badge = :badge";
    $badge_check_stmt = $pdo->prepare($badge_check_sql);
    $badge_check_stmt->execute([
        'user_id' => $user_id,
        'badge' => 'Outdoor Adventure'
    ]);
    $badge_exists = $badge_check_stmt->fetchColumn() > 0;

    // Insert the badge accomplishment if progress is 100% and badge does not exist
    if ($progress_percentage == 100 && !$badge_exists) {
        $badge_sql = "INSERT INTO tbl_badge_accomplishment (user_id, badge, color, icon, date_created) 
                      VALUES (:user_id, :badge, :color, :icon, :date_created)";
        $badge_stmt = $pdo->prepare($badge_sql);
        $badge_stmt->execute([
            'user_id' => $user_id,
            'badge' => 'Outdoor Adventure',
            'color' => '#3cAEA3',
            'icon' => 'fa-hiking',
            'date_created' => (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s')
        ]);
    }

    // Return the result as JSON
    echo json_encode([
        'mt_batulao' => $mt_batulao,
        'mt_maculot' => $mt_maculot,
        'mt_talumpok' => $mt_talumpok,
        'malagaslas_falls' => $malagaslas_falls,
        'progress_percentage' => $progress_percentage
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>