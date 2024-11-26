<?php
include '../../../inc/config.php';

// Suppress warnings and notices
error_reporting(E_ERROR | E_PARSE);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewId = intval($_POST['review_id']);
    $reviewText = $_POST['review'];

    try {
        // Fetch existing images
        $query = "SELECT images FROM tbl_review WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);
        $stmt->execute();
        $existingImages = $stmt->rowCount() > 0 ? explode(',', $stmt->fetch(PDO::FETCH_ASSOC)['images']) : [];

        // Handle new image uploads
        $uploadedImages = [];
        if (!empty($_FILES['new_images']['name'][0])) {
            foreach ($_FILES['new_images']['tmp_name'] as $key => $tmp_name) {
                $imageName = uniqid() . '-' . $_FILES['new_images']['name'][$key];
                $uploadPath = "../../../admin/review_image/" . $imageName;

                if (move_uploaded_file($tmp_name, $uploadPath)) {
                    $uploadedImages[] = $imageName;
                }
            }
        }

        // Combine images
        $allImages = array_merge($existingImages, $uploadedImages);
        $allImagesString = implode(',', $allImages);

        // Update the review
        $query = "UPDATE tbl_review SET review = :review, images = :images WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':review', $reviewText, PDO::PARAM_STR);
        $stmt->bindParam(':images', $allImagesString, PDO::PARAM_STR);
        $stmt->bindParam(':id', $reviewId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update the review.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>