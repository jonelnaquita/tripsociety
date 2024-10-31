<?php
session_start();
include '../../../inc/config.php';

// Fetch post_id from the AJAX request
$post_id = $_POST['post_id'];
$user_id = $_SESSION['user']; // Assuming you have a session variable for the logged-in user

try {
    // Prepare SQL statement to fetch user, post, comments, reactions, and check if the user reacted
    $query = "
        SELECT p.id, p.image, p.post, p.location, u.profile_img, u.name, u.username,
            (SELECT COUNT(*) FROM tbl_post_comment pc WHERE pc.post_id = p.id) AS comment_count,
            (SELECT COUNT(*) FROM tbl_reaction r WHERE r.post_id = p.id) AS reaction_count,
            (SELECT COUNT(*) FROM tbl_reaction r WHERE r.post_id = p.id AND r.user_id = :user_id) AS user_reacted
        FROM tbl_user u
        LEFT JOIN tbl_post p ON u.id = p.user_id
        WHERE p.id = :post_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the post
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    // Now, fetch comments if they exist
    $comments = [];
    if ($post) {
        $commentsQuery = "
        SELECT pc.message, pc.date_created, u.profile_img, u.name, u.username
        FROM tbl_post_comment pc
        LEFT JOIN tbl_user u ON pc.user_id = u.id
        WHERE pc.post_id = :post_id
    ";

        $commentsStmt = $pdo->prepare($commentsQuery);
        $commentsStmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $commentsStmt->execute();
        $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Prepare the response
    $response = [
        'post' => $post,
        'comments' => $comments,
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>