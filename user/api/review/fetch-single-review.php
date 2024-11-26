<?php
include '../../../inc/config.php';

if (isset($_GET['id'])) {
    $reviewId = intval($_GET['id']);

    try {
        // Query to fetch the review
        $query = "SELECT review, images FROM tbl_review WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $images = !empty($row['images']) ? explode(',', $row['images']) : [];
            echo json_encode([
                'success' => true,
                'data' => [
                    'review' => $row['review'],
                    'images' => $images,
                ],
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Review not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>