<?php
session_start();
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the session variable for admin is set
    if (!isset($_SESSION['admin'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
        exit;
    }

    $admin_id = $_SESSION['admin']; // Assuming admin ID is stored in session
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Get the entered password

    try {
        // Prepare the SQL statement to fetch the current password hash
        $stmt = $pdo->prepare("SELECT password FROM tbl_account WHERE id = :admin_id");
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->execute();

        // Fetch the current password hash
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($currentUser && password_verify($password, $currentUser['password'])) {
            // Proceed with the update if the password matches
            $stmt = $pdo->prepare("UPDATE tbl_account SET username = :username, email = :email WHERE id = :admin_id");

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':admin_id', $admin_id);

            // Execute the statement
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
            }
        } else {
            // Password does not match
            echo json_encode(['success' => false, 'message' => 'Incorrect password. Please try again.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>