<?php
include '../../../inc/config.php'; // Database configuration file

header('Content-Type: application/json');

try {
    // Check if email and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Fetch the user from the database
        $query = "SELECT * FROM tbl_account WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the hashed password
            if (password_verify($password, $user['password'])) {
                echo json_encode(['success' => true, 'message' => 'Login successful!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Email not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>