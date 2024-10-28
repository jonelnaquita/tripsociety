<?php
include '../inc/config.php';
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify if the token exists in the database
    $verifyTokenSql = "SELECT id FROM tbl_user WHERE verification_token = :token";
    $stmt = $pdo->prepare($verifyTokenSql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // Token is valid, verify the user
        $updateSql = "UPDATE tbl_user SET is_verified = 1, verification_token = NULL WHERE id = :id";
        $stmt = $pdo->prepare($updateSql);
        $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Store the user ID in the session
        $_SESSION['user'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['profile_img'] = $user['profile_img'];
        $_SESSION['cover_img'] = $user['cover_img'];
        $_SESSION['travel_preferences'] = $user['travel_preferences'];

        echo 'Your email has been verified successfully!';
        // Redirect to travel preference page
        header('Location: travel_preference.php');
        exit(); // Make sure to exit after redirection
    } else {
        echo 'Invalid token.';
    }
} else {
    echo 'Token is already expired.';
}
?>