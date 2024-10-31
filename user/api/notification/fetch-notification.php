<?php
include '../../../inc/config.php';

// Fetch user_id from the AJAX request
$user_id = $_POST['user_id'];

// Function to calculate time elapsed
function time_elapsed_string($datetime, $full = false)
{
    $timezone = new DateTimeZone('Asia/Manila');
    $now = new DateTime('now', $timezone);
    $ago = new DateTime($datetime, $timezone);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [];
    $units = [
        'year' => $diff->y,
        'month' => $diff->m,
        'week' => $diff->w,
        'day' => $diff->d,
        'hour' => $diff->h,
        'minute' => $diff->i,
        'second' => $diff->s,
    ];

    foreach ($units as $key => $value) {
        if ($value) {
            $string[] = $value . ' ' . $key . ($value > 1 ? 's' : '');
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

// Fetch reactions
$reactionQuery = "SELECT p.id as post_id, p.post, u.name, u.profile_img, r.date_created
    FROM tbl_reaction r
    LEFT JOIN tbl_user u ON r.user_id = u.id
    LEFT JOIN tbl_post p ON r.post_id = p.id
    WHERE p.user_id = :user_id AND r.user_id != :user_id AND r.viewed = 0";
$stmt = $pdo->prepare($reactionQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format the date_created for reactions
foreach ($reactions as &$reaction) {
    $reaction['elapsed_time'] = time_elapsed_string($reaction['date_created']);
    $reaction['type'] = 'reaction';
}

// Fetch comments
$commentQuery = "SELECT c.post_id, u.name, u.profile_img, c.message AS comment_text, c.date_created
FROM tbl_post_comment c
JOIN tbl_user u ON c.user_id = u.id
JOIN tbl_post p ON c.post_id = p.id
WHERE p.user_id = :user_id AND c.user_id != :user_id AND c.viewed = 0";
$stmt = $pdo->prepare($commentQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format the date_created for comments
foreach ($comments as &$comment) {
    $comment['elapsed_time'] = time_elapsed_string($comment['date_created']);
    $comment['type'] = 'comment';
}

// Fetch reviews
$reviewQuery = "SELECT tr.*, tu.name, tu.profile_img, trr.id AS review_id, trr.date_created AS reaction_date
FROM tbl_review tr
JOIN tbl_review_reaction trr ON tr.id = trr.review_id
JOIN tbl_user tu ON trr.user_id = tu.id
WHERE tr.user_id = :user_id AND trr.user_id != :user_id AND trr.viewed = 0";
$stmt = $pdo->prepare($reviewQuery);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Format the date_created for reviews
foreach ($reviews as &$review) {
    $review['elapsed_time'] = time_elapsed_string($review['date_created']);
    $review['type'] = 'review';
}

// Merge reactions, comments, and reviews
$notifications = array_merge($reactions, $comments, $reviews);

// Sort notifications by date_created in descending order
usort($notifications, function ($a, $b) {
    return strtotime($b['date_created']) - strtotime($a['date_created']);
});

// Return the results as JSON
$response = [
    'notifications' => $notifications,
];

echo json_encode($response);
?>