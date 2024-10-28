<?php
include '../../../inc/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['user_id'];
    $postId = $_POST['post_id'];

    // Check if the user has already reacted
    $reaction_statement = $pdo->prepare("SELECT 1 FROM tbl_reaction WHERE user_id = ? AND post_id = ?");
    $reaction_statement->execute([$userId, $postId]);
    $has_reacted = $reaction_statement->fetchColumn();

    if ($has_reacted) {
        // If reacted, remove the reaction
        $delete_statement = $pdo->prepare("DELETE FROM tbl_reaction WHERE user_id = ? AND post_id = ?");
        $delete_statement->execute([$userId, $postId]);
        $icon_class = 'far fa-heart'; // Outline heart for unliked
    } else {
        // If not reacted, add the reaction
        $insert_statement = $pdo->prepare("INSERT INTO tbl_reaction (user_id, post_id) VALUES (?, ?)");
        $insert_statement->execute([$userId, $postId]);
        $icon_class = 'fas fa-heart'; // Solid heart for liked
    }

    // Get the updated reaction count
    $count_statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_reaction WHERE post_id = ?");
    $count_statement->execute([$postId]);
    $reaction_count = $count_statement->fetchColumn();

    // Return the updated data as JSON
    echo json_encode([
        'success' => true,
        'icon_class' => $icon_class,
        'reaction_count' => $reaction_count
    ]);
} else {
    // Handle incorrect request method
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>