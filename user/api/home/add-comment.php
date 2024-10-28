<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['post_id'];
    $message = $_POST['message'];

    $userId = isset($_SESSION['user']) ? $_SESSION['user'] : null; // Check if user ID exists in session
    if ($userId === null) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit; // Stop execution if user ID is not set
    }


    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("INSERT INTO tbl_post_comment (post_id, user_id, message) VALUES (:post_id, :user_id, :message)");

        // Bind parameters
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert comment.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        // Close the connection
        $pdo = null;
    }
}
?>