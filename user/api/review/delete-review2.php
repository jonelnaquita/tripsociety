<?php
include '../../../inc/config.php';
header('Content-Type: application/json');

try {
    // Check if ID is provided
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Review ID is required.']);
        exit;
    }

    // Sanitize and validate input
    $reviewId = intval($_POST['id']);

    // Delete the review
    $stmt = $pdo->prepare("DELETE FROM tbl_review WHERE id = :id");
    $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Review deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No review found with the provided ID.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
