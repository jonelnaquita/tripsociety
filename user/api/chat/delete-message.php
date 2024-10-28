<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id']; // Sender ID from the AJAX request
    $receiver_id = $_POST['receiver_id']; // Receiver ID from the AJAX request

    try {
        // Prepare the SQL delete statement
        $sql = "DELETE FROM tbl_message WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);

        // Execute the deletion
        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true]);
        } else {
            // Return an error response if deletion fails
            echo json_encode(['success' => false, 'message' => 'Failed to delete messages.']);
        }
    } catch (PDOException $e) {
        // Handle any errors during the database operation
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return an error if the request method is not POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>