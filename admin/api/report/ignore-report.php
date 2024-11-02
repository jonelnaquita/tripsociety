<?php
// Connect to your database
include '../../../inc/config.php';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the post ID from AJAX request
        $postId = intval($_POST['post_id']);

        // Prepare and execute the update query
        $stmt = $pdo->prepare("UPDATE tbl_post_report SET status = 1 WHERE id = :post_id");
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Report status updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update report status.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>