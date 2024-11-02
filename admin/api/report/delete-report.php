<?php
// Connect to your database
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the post ID from the AJAX request
    $postId = intval($_POST['post_id']);

    // Fetch the report details to determine category
    $stmt = $pdo->prepare("
        SELECT
            pr.id AS report_id, 
            pr.user_id,
            pr.post_id, 
            pr.category,
            pr.violation 
        FROM tbl_post_report pr
        WHERE pr.id = :postId
    ");
    $stmt->execute(['postId' => $postId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($report) {
        $reportId = $report['report_id'];
        $postId = $report['post_id'];
        $userId = $report['user_id'];
        $category = $report['category'];
        $violation = $report['violation'];

        // Delete based on category
        if ($category === 'Post') {
            // Delete from tbl_post
            $deleteStmt = $pdo->prepare("DELETE FROM tbl_post WHERE id = :postId");
        } elseif ($category === 'Review') {
            // Delete from tbl_review
            $deleteStmt = $pdo->prepare("DELETE FROM tbl_review WHERE id = :postId");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid category.']);
            exit;
        }

        // Execute the delete statement
        $deleteStmt->execute(['postId' => $postId]);

        // Insert into tbl_deleted_report
        $insertStmt = $pdo->prepare("
            INSERT INTO tbl_deleted_report (user_id, post_id, category, violation, date_created)
            VALUES (:user_id, :post_id, :category, :violation, NOW())
        ");
        $insertStmt->execute([
            'user_id' => $userId,
            'post_id' => $postId,
            'category' => $category,
            'violation' => $violation
        ]);

        // Update the tbl_post_report to set status to 1
        $updateStmt = $pdo->prepare("UPDATE tbl_post_report SET status = 1 WHERE id = :reportId");
        $updateStmt->execute(['reportId' => $reportId]);

        // Send a success response
        echo json_encode(['status' => 'success', 'message' => 'Post deleted and report updated successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Report not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>