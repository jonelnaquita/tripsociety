<?php
session_start();
include '../../../inc/config.php';

$response = ['success' => false, 'message' => ''];

// Ensure timezone and get current timestamp
date_default_timezone_set('Asia/Manila');
$currentTimestamp = date('Y-m-d H:i:s');

// Check user session
$user_id = $_SESSION['user'] ?? null;
if (!$user_id) {
    $response['message'] = 'User not logged in.';
    echo json_encode($response);
    exit;
}

// Retrieve and validate POST data
$post = $_POST['post'] ?? '';
$location = $_POST['location'] ?? '';

// Prepare for image upload
$imagePaths = [];
$uploadDir = '../../../admin/post_image/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true); // Create directory if it does not exist
}

if (isset($_FILES['images']['tmp_name']) && is_array($_FILES['images']['tmp_name'])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmpName, $filePath)) {
                $imagePaths[] = $fileName;
            } else {
                $response['message'] = 'Failed to upload image: ' . $fileName;
                echo json_encode($response);
                exit;
            }
        }
    }
}

$imagesString = implode(',', $imagePaths);

try {
    // Prepare the base query
    $query = "INSERT INTO tbl_post (user_id, post, image, date_created";
    $queryValues = "VALUES (:user_id, :post, :images, :date_created";

    // Add location only if it's not empty
    if (!empty($location)) {
        $query .= ", location"; // Add location column
        $queryValues .= ", :location"; // Add location placeholder
    }

    $query .= ") " . $queryValues . ")";
    $stmt = $pdo->prepare($query);

    // Bind parameters
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':post', $post);
    $stmt->bindParam(':images', $imagesString);
    $stmt->bindParam(':date_created', $currentTimestamp);

    // Bind location only if it's not empty
    if (!empty($location)) {
        $stmt->bindParam(':location', $location);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Post published successfully.';
    } else {
        $response['message'] = 'Failed to publish post!';
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?>