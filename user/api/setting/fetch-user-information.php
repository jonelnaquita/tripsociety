<?php
session_start(); // Start session for user identification
include '../../../inc/config.php';

header('Content-Type: application/json'); // Set content type for JSON response

try {
    // Fetch user data
    $stmt = $pdo->prepare("SELECT name, username, email, location FROM tbl_user WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user']]); // Make sure session variable is correctly set
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>