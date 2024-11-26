<?php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewId = intval($_POST['review_id']);
    $imageName = $_POST['image_name'];

    try {
        // Path to the image
        $imagePath = "../../../admin/review_image/" . $imageName;

        // Check if the file exists and delete it
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Remove the image from the database
        $query = "SELECT images FROM tbl_review WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
        $stmt->execute();

        // Get the existing images and split them into an array
        $existingImages = explode(',', $stmt->fetch(PDO::FETCH_ASSOC)['images']);

        // Remove the deleted image from the array
        $updatedImages = array_diff($existingImages, [$imageName]);

        // Update the database with the remaining images
        $query = "UPDATE tbl_review SET images = :images WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':images', implode(',', $updatedImages), PDO::PARAM_STR);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update review images.']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>