<?php
session_start();
include '../../../inc/config.php';

// Fetch post_id from the AJAX request
$post_id = $_POST['post_id'];
$user_id = $_SESSION['user']; // Assuming you have a session variable for the logged-in user

try {
    // Prepare SQL statement to fetch user, post, comments, reactions, and check if the user reacted
    $query = "
        SELECT pc.message, pc.date_created, u.profile_img, u.name, u.username, p.image, p.post, p.location,
               (SELECT COUNT(*) FROM tbl_reaction r WHERE r.post_id = p.id) AS reaction_count,
               (SELECT COUNT(*) FROM tbl_reaction r WHERE r.post_id = p.id AND r.user_id = :user_id) AS user_reacted
        FROM tbl_user u
        LEFT JOIN tbl_post p ON u.id = p.user_id
        LEFT JOIN tbl_post_comment pc ON p.id = pc.post_id
        WHERE pc.post_id = :post_id
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Bind the user ID to check for reaction
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any reactions exist and determine if the user has reacted
    if (count($result) > 0) {
        // Add the user reaction status to the first result
        $result[0]['user_reacted'] = $result[0]['user_reacted'] > 0;
    }

    // Return the results as JSON
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>