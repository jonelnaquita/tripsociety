<?php
session_start();
include '../../../inc/config.php';

$user_id = $_SESSION['user'];

// Query to count posts and reviews by the user, limiting the results to 2
$sql_post = "SELECT COUNT(*) as post_count FROM (SELECT 1 FROM tbl_post WHERE user_id = :user_id LIMIT 2) as limited_posts";
$sql_review = "SELECT COUNT(*) as review_count FROM (SELECT 1 FROM tbl_review WHERE user_id = :user_id LIMIT 2) as limited_reviews";

$stmt_post = $pdo->prepare($sql_post);
$stmt_post->execute(['user_id' => $user_id]);
$post_result = $stmt_post->fetch(PDO::FETCH_ASSOC);

$stmt_review = $pdo->prepare($sql_review);
$stmt_review->execute(['user_id' => $user_id]);
$review_result = $stmt_review->fetch(PDO::FETCH_ASSOC);

$post_count = $post_result['post_count'];
$review_count = $review_result['review_count'];

// Calculate the progress
$max_criteria = 2; // You need 2 posts and 2 reviews
$total_progress = ($post_count + $review_count);
$progress_percentage = min(($total_progress / 4) * 100, 100); // max 100%

// Return response
$response = [
    'post_count' => min($post_count, $max_criteria), // max 2
    'review_count' => min($review_count, $max_criteria), // max 2
    'progress_percentage' => $progress_percentage,
];

echo json_encode($response);
?>