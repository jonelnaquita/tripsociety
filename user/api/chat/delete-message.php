<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id']; // Sender ID from the AJAX request
    $receiver_id = $_POST['receiver_id']; // Receiver ID from the AJAX request
    $current_user_id = $_SESSION['user']; // Get the current user ID from the session

    try {
        // Prepare the SQL update statement
        $sql = "UPDATE tbl_message 
                SET 
                    deleted_by = CASE 
                        WHEN deleted_by IS NULL OR deleted_by = '' THEN :current_user_id
                        ELSE CONCAT(deleted_by, ',', :current_user_id)
                    END
                WHERE (sender_id = :sender_id AND receiver_id = :receiver_id) 
                   OR (sender_id = :receiver_id AND receiver_id = :sender_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sender_id', $sender_id, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_id', $receiver_id, PDO::PARAM_INT);
        $stmt->bindParam(':current_user_id', $current_user_id, PDO::PARAM_INT);

        // Execute the update
        if ($stmt->execute()) {
            // Return a success response
            echo json_encode(['success' => true]);
        } else {
            // Return an error response if update fails
            echo json_encode(['success' => false, 'message' => 'Failed to update conversation status.']);
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