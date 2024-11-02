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
        WHERE p.user_id = :user_id AND r.user_id != :user_id AND r.viewed = 0
    ";
    $stmt = $pdo->prepare($reactionQuery);
    $stmt->execute(['user_id' => $user_id]);
    $reactionCount = $stmt->fetch(PDO::FETCH_ASSOC)['reaction_count'];

    // Query for counting comments
    $commentQuery = "
        SELECT COUNT(*) AS comment_count
        FROM tbl_post_comment c
        JOIN tbl_post p ON c.post_id = p.id
        WHERE p.user_id = :user_id AND c.user_id != :user_id AND c.viewed = 0
    ";
    $stmt = $pdo->prepare($commentQuery);
    $stmt->execute(['user_id' => $user_id]);
    $commentCount = $stmt->fetch(PDO::FETCH_ASSOC)['comment_count'];

    // Query for counting reviews
    $reviewQuery = "
        SELECT COUNT(*) AS review_count
        FROM tbl_review_reaction trr
        JOIN tbl_review tr ON trr.review_id = tr.id
        WHERE tr.user_id = :user_id AND trr.user_id != :user_id AND trr.viewed = 0
    ";
    $stmt = $pdo->prepare($reviewQuery);
    $stmt->execute(['user_id' => $user_id]);
    $reviewCount = $stmt->fetch(PDO::FETCH_ASSOC)['review_count'];



    // Query for counting announcements
    $announcementQuery = "
        SELECT COUNT(*) AS announcement_count 
        FROM tbl_announcement ta
        LEFT JOIN tbl_announcement_viewed tav ON tav.announcement_id = ta.id AND tav.user_id = :user_id
        WHERE tav.id IS NULL
    ";

    $stmt = $pdo->prepare($announcementQuery);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the announcement count
    $announcementCount = $stmt->fetch(PDO::FETCH_ASSOC)['announcement_count'];

    // Query for counting travel companion
    $companionQuery = "SELECT COUNT(*) AS companion_count 
                   FROM tbl_travel_companion 
                   WHERE (companion_id = :user_id) AND viewed = 0";
    $stmt = $pdo->prepare($companionQuery); // Use prepare instead of query for parameterized queries
    $stmt->bindParam(':user_id', $user_id); // Assuming $userId is defined elsewhere
    $stmt->execute();
    $companionCount = $stmt->fetch(PDO::FETCH_ASSOC)['companion_count'];

    // Total notifications count
    $totalNotifications = $reactionCount + $commentCount + $reviewCount + $announcementCount + $companionCount;

    // Return the total notification count as JSON
    echo json_encode(['total_notifications' => $totalNotifications]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>