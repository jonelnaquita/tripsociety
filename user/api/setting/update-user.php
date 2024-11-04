<?php
session_start(); // Start session for user identification
include '../../../inc/config.php';

header('Content-Type: application/json'); // Set content type for JSON response

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'User session not found.']);
    exit;
}

try {
    // Get input data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $location = $_POST['location'];
    $travel_preferences = $_POST['travel_preferences'];
    $password = $_POST['password'];

    if (empty($name) || empty($username) || empty($email) || empty($location) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Verify current password
    $stmt = $pdo->prepare("SELECT password FROM tbl_user WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['user']]); // Ensure this matches session variable
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Check for existing username or email
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_user WHERE (username = :username OR email = :email) AND id != :id");
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'id' => $_SESSION['user'] // Exclude current user's id
        ]);

        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
            exit;
        }

        // Update user data
        $stmt = $pdo->prepare("UPDATE tbl_user SET name = :name, username = :username, email = :email, location = :location, travel_preferences = :travel_preferences WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'location' => $location,
            'travel_preferences' => $travel_preferences,
            'id' => $_SESSION['user']
        ]);

        echo json_encode(['success' => true, 'message' => 'Account updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage()); // Log the error message to the server log
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>