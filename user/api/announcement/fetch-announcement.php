<?php
include '../../../inc/config.php';

// Start the session to access the user ID
session_start();

// Get the announcement ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if the user is logged in and has a valid session
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'];
} else {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['message' => 'User not authenticated']);
    exit;
}

if ($id > 0) {
    // Prepare SQL query to fetch announcement details
    $sql = "SELECT title, description, image, DATE_FORMAT(date_created, '%M %d, %Y') AS formatted_date
            FROM tbl_announcement
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if announcement exists
    if ($announcement) {
        // Check if the user has already viewed this announcement
        $checkSql = "SELECT COUNT(*) FROM tbl_announcement_viewed
                     WHERE announcement_id = :announcement_id AND user_id = :user_id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':announcement_id', $id, PDO::PARAM_INT);
        $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $checkStmt->execute();
        $viewedCount = $checkStmt->fetchColumn();

        // If not viewed, insert a new entry
        if ($viewedCount == 0) {
            $insertSql = "INSERT INTO tbl_announcement_viewed (announcement_id, user_id, viewed) 
                          VALUES (:announcement_id, :user_id, 1)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':announcement_id', $id, PDO::PARAM_INT);
            $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $insertStmt->execute();
        }

        // Send JSON response
        header('Content-Type: application/json');
        echo json_encode($announcement);
    } else {
        // No announcement found
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['message' => 'Announcement not found']);
    }
} else {
    // Invalid ID
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['message' => 'Invalid announcement ID']);
}
?>