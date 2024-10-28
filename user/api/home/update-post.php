<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editPostId = $_POST['post_id'];
    $postText = $_POST['post'];
    $location = $_POST['editLocation'];
    $images = isset($_FILES['images']) ? $_FILES['images'] : null;

    // Prepare the SQL statement
    $sql = "UPDATE tbl_post SET post = :post, location = :location";

    // Check if images were uploaded
    if ($images && count($images['name']) > 0) {
        // Prepare for images
        $imageNames = [];
        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images['error'][$i] === 0) {
                $imageName = uniqid() . '-' . basename($images['name'][$i]);
                move_uploaded_file($images['tmp_name'][$i], "../../../admin/post_image/" . $imageName);
                $imageNames[] = $imageName;
            }
        }
        // Join the image names with commas
        $imageList = implode(',', $imageNames);
        $sql .= ", image = :image"; // Add to the SQL statement
    }

    $sql .= " WHERE id = :post_id"; // Complete the SQL statement with WHERE clause

    // Prepare and execute the statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':post', $postText);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':post_id', $editPostId);

    // Bind image parameter only if images were uploaded
    if (isset($imageList)) {
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