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
        } else {
            // Log and return an error if the image doesn't exist
            echo json_encode(['success' => false, 'message' => 'Image file does not exist.']);
            exit;
        }

        // Remove the image from the database
        $query = "SELECT images FROM tbl_review WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch images and handle potential issues if no images are found
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            echo json_encode(['success' => false, 'message' => 'Review not found.']);
            exit;
        }

        $existingImages = explode(',', $result['images']);
        // Remove the deleted image from the array
        $updatedImages = array_diff($existingImages, [$imageName]);

        // If the image list is empty after removal, set the images column to null or an empty string
        $updatedImagesStr = empty($updatedImages) ? '' : implode(',', $updatedImages);

        // Update the database with the remaining images
        $query = "UPDATE tbl_review SET images = :images WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':images', $updatedImagesStr, PDO::PARAM_STR);
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