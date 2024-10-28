<?php
session_start();
include '../../../inc/config.php';  // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Query to get user data based on the email
        $query = "SELECT * FROM tbl_user WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_verified'] == 0) {
                // User not verified
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Your account is not yet verified. Please check your email for the verification link.'
                ]);
            } else {
                // Correct password, log in the user
                $_SESSION['status'] = $user['status'];
                $_SESSION['user'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profile_img'] = $user['profile_img'];
                $_SESSION['cover_img'] = $user['cover_img'];
                $_SESSION['travel_preferences'] = $user['travel_preferences'];

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Welcome ' . $_SESSION['name'] . '!'
                ]);
            }
        } else {
            // Invalid email or password
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid email or password.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
?>