<?php
session_start();
include '../../../inc/config.php';

if (isset($_POST['review_id'])) {
    $user_id = $_SESSION['user']; // Assuming user_id is stored in the session
    $review_id = $_POST['review_id'];

    try {
        // Check if the user already liked the review
        $sql = "SELECT * FROM tbl_review_reaction WHERE user_id = :user_id AND review_id = :review_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':review_id' => $review_id
        ]);

        if ($stmt->rowCount() > 0) {
            // User already liked the review, so remove the like
            $sql = "DELETE FROM tbl_review_reaction WHERE user_id = :user_id AND review_id = :review_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $user_id,
                ':review_id' => $review_id
            ]);

            echo json_encode(['status' => 'unliked']);
        } else {
            // User has not liked the review, so add the like
            $sql = "INSERT INTO tbl_review_reaction (user_id, review_id) VALUES (:user_id, :review_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $user_id,
                ':review_id' => $review_id
            ]);

            echo json_encode(['status' => 'liked']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>