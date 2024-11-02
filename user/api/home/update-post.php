<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editPostId = $_POST['post_id'];
    $postText = $_POST['post'];
    $location = $_POST['editLocation'];
    $deletedImages = isset($_POST['deletedImages']) ? explode(',', $_POST['deletedImages']) : [];
    $images = isset($_FILES['images']) ? $_FILES['images'] : null;

    // Fetch existing images from the database
    $stmt = $pdo->prepare("SELECT image FROM tbl_post WHERE id = :post_id");
    $stmt->bindParam(':post_id', $editPostId);
    $stmt->execute();
    $existingImages = $stmt->fetchColumn();
    $existingImagesArray = !empty($existingImages) ? explode(',', $existingImages) : [];

    // Remove deleted images from the list
    $updatedImagesArray = array_diff($existingImagesArray, $deletedImages);

    // Handle new image uploads
    $imageNames = [];
    if ($images && count($images['name']) > 0) {
        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images['error'][$i] === 0) {
                $imageName = uniqid() . '-' . basename($images['name'][$i]);
                move_uploaded_file($images['tmp_name'][$i], "../../../admin/post_image/" . $imageName);
                $imageNames[] = $imageName;
            }
        }
    }

    // Merge updated images with newly uploaded images
    $allImages = array_merge($updatedImagesArray, $imageNames);
    $imageList = implode(',', $allImages);

    // Update the database
    $sql = "UPDATE tbl_post SET post = :post, location = :location, image = :image WHERE id = :post_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post', $postText);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':image', $imageList);
    $stmt->bindParam(':post_id', $editPostId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
?>