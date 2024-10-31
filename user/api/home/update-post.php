<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editPostId = $_POST['post_id'];
    $postText = $_POST['post'];
    $location = $_POST['editLocation'];
    $images = isset($_FILES['images']) ? $_FILES['images'] : null;

    // Fetch existing images from the database
    $stmt = $pdo->prepare("SELECT image FROM tbl_post WHERE id = :post_id");
    $stmt->bindParam(':post_id', $editPostId);
    $stmt->execute();
    $existingImages = $stmt->fetchColumn(); // Get existing images
    $existingImagesArray = !empty($existingImages) ? explode(',', $existingImages) : [];

    // Prepare the SQL statement
    $sql = "UPDATE tbl_post SET post = :post, location = :location";

    // Check if images were uploaded
    if ($images && count($images['name']) > 0) {
        $imageNames = [];
        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images['error'][$i] === 0) {
                $imageName = uniqid() . '-' . basename($images['name'][$i]);
                move_uploaded_file($images['tmp_name'][$i], "../../../admin/post_image/" . $imageName);
                $imageNames[] = $imageName;
            }
        }
        // Merge existing images with newly uploaded images
        $allImages = array_merge($existingImagesArray, $imageNames);
        $imageList = implode(',', $allImages); // Join the image names with commas
        $sql .= ", image = :image"; // Add to the SQL statement
    } else {
        $imageList = implode(',', $existingImagesArray); // No new images, keep existing
    }

    $sql .= " WHERE id = :post_id"; // Complete the SQL statement with WHERE clause

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post', $postText);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':post_id', $editPostId);

    // Bind image parameter only if images were uploaded or exist
    if (!empty($imageList)) {
        $stmt->bindParam(':image', $imageList);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>