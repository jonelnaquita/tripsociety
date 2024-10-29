<?php
session_start();
include '../../../inc/config.php';

if (isset($_POST['user_id']) && isset($_SESSION['user'])) {
    $userId = $_POST['user_id'];
    $sessionId = $_SESSION['user'];

    // Prepare the SQL statement to update the viewed status
    $stmt = $pdo->prepare("UPDATE tbl_travel_companion SET viewed = 1 WHERE user_id = :userId AND companion_id = :companionId");

    // Execute the query with bound parameters
    if ($stmt->execute(['userId' => $userId, 'companionId' => $sessionId])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>