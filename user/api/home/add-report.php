<?php
session_start();
include '../../../inc/config.php'; // Make sure to include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    $category = 'Post';
    $violation = $_POST['violation'];

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO tbl_post_report (user_id, post_id, category, violation) VALUES (?, ?, ?, ?)");

    if ($stmt->execute([$user_id, $post_id, $category, $violation])) {
        echo json_encode(['success' => true, 'message' => 'Review reported successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to report post.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>