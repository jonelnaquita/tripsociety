<?php
include '../../../inc/config.php';

// Fetch the post ID and type (reaction/comment) from the AJAX request
$post_id = $_POST['post_id'];
$type = $_POST['type'];

// Update the viewed status based on the type
if ($type === 'reaction') {
    $updateQuery = "UPDATE tbl_reaction SET viewed = 1 WHERE post_id = :post_id";
} elseif ($type === 'comment') {
    $updateQuery = "UPDATE tbl_post_comment SET viewed = 1 WHERE post_id = :post_id";
} else {
    // Invalid type
    echo json_encode(['success' => false, 'message' => 'Invalid type']);
    exit;
}

$stmt = $pdo->prepare($updateQuery);
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}
?>