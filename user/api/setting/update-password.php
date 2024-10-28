<?php
session_start(); // Start session for user identification
include '../../../inc/config.php';

header('Content-Type: application/json'); // Set content type for JSON response

try {
    // Get input data
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM tbl_user WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user']]); // Ensure this matches session variable
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($currentPassword, $user['password'])) {
        // Update user password
        $stmt = $pdo->prepare("UPDATE tbl_user SET password = :password WHERE id = :id");
        $stmt->execute([
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'id' => $_SESSION['user'] // Ensure this matches session variable
        ]);

        echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage()); // Log the error message to the server log
    echo json_encode(['success' => false, 'message' => 'An error occurred.']);
}
?>