<?php
session_start();
include '../../../inc/config.php';

if (isset($_POST['post_id']) && isset($_SESSION['user'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user'];

    // Fetch post to verify user owns it
    $stmt = $pdo->prepare("SELECT * FROM tbl_post WHERE id = :post_id AND user_id = :user_id");
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
    $post = $stmt->fetch();

    if ($post) {
        // Delete the post
        $delete_stmt = $pdo->prepare("DELETE FROM tbl_post WHERE id = :post_id");
        $delete_stmt->execute(['post_id' => $post_id]);

        if ($delete_stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>