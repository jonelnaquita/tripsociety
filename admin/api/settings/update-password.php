<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if session variable for admin is set
    if (!isset($_SESSION['admin'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
        exit;
    }

    $admin_id = $_SESSION['admin']; // Assuming the admin ID is stored in the session
    $newPassword = $_POST['new_password'];

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    try {
        // Update the password in tbl_account table
        $stmt = $pdo->prepare("UPDATE tbl_account SET password = :password WHERE id = :admin_id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':admin_id', $admin_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password updated successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>