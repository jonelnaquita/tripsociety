<?php
include '../../../inc/config.php';

// Get the new password and token from the request
$password = $_POST['password'];
$token = $_POST['token']; // Now getting token from POST request

try {
    // Verify token and get user email
    $stmt = $pdo->prepare("SELECT id FROM tbl_user WHERE reset_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Password hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update password in the database
        $stmt = $pdo->prepare("UPDATE tbl_user SET password = :password, reset_token = NULL WHERE id = :userId");
        $stmt->execute(['password' => $hashedPassword, 'userId' => $user['id']]); // Assuming user_id is found

        echo json_encode(['response' => 'Success', 'message' => 'Password has been updated successfully.']);
    } else {
        echo json_encode(['response' => 'Error', 'message' => 'Invalid token.']);
    }
} catch (Exception $e) {
    echo json_encode(['response' => 'Error', 'message' => 'Could not update password.']);
}
?>