<?php
include '../../../inc/config.php'; // Database connection
session_start();

$user_id = $_SESSION['user']; // Change this to the current logged-in user's ID

try {
    // Query for counting reactions
    $reactionQuery = "
        SELECT COUNT(*) AS reaction_count
        FROM tbl_reaction r
        JOIN tbl_post p ON r.post_id = p.id
        WHERE p.user_id = :user_id AND r.user_id != :user_id
    ";
    $stmt = $pdo->prepare($reactionQuery);
    $stmt->execute(['user_id' => $user_id]);
    $reactionCount = $stmt->fetch(PDO::FETCH_ASSOC)['reaction_count'];

    // Query for counting comments
    $commentQuery = "
        SELECT COUNT(*) AS comment_count
        FROM tbl_post_comment c
        JOIN tbl_post p ON c.post_id = p.id
        WHERE p.user_id = :user_id AND c.user_id != :user_id
    ";
    $stmt = $pdo->prepare($commentQuery);
    $stmt->execute(['user_id' => $user_id]);
    $commentCount = $stmt->fetch(PDO::FETCH_ASSOC)['comment_count'];

    // Query for counting announcements
    $announcementQuery = "SELECT COUNT(*) AS announcement_count FROM tbl_announcement";
    $stmt = $pdo->query($announcementQuery);
    $announcementCount = $stmt->fetch(PDO::FETCH_ASSOC)['announcement_count'];

    // Total notifications count
    $totalNotifications = $reactionCount + $commentCount + $announcementCount;

    // Return the total notification count as JSON
    echo json_encode(['total_notifications' => $totalNotifications]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>