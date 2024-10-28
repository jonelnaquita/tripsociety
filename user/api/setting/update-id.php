<?php
session_start();
header('Content-Type: application/json'); // Set header to return JSON
include '../../../inc/config.php';

$response = ['success' => false, 'message' => ''];

try {
    // Check if user ID is set in session
    if (!isset($_SESSION['user'])) {
        throw new Exception('User not logged in.');
    }

    $userId = $_SESSION['user']; // Assuming the user ID is stored in the session

    // Fetch the username from tbl_user
    $stmt = $pdo->prepare("SELECT username FROM tbl_user WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception('User not found.');
    }

    $username = $user['username']; // Get the username

    if (!isset($_FILES['fileFront']) || !isset($_FILES['fileBack'])) {
        throw new Exception('Both the front and back of the ID must be uploaded.');
    }

    $frontFile = $_FILES['fileFront'];
    $backFile = $_FILES['fileBack'];

    // Debugging step: log file details to see if files are received
    error_log(print_r($_FILES, true));

    // Define the directory to save uploaded files
    $uploadDir = '../../../admin/id/';

    // Function to validate and move file
    function handleFileUpload($file, $uploadDir, $username, $type)
    {
        // Define allowed file types
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Check file type
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and GIF are allowed.');
        }

        // Generate a unique file name
        $fileName = $username . '_' . $type . '-' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

        $filePath = $uploadDir . $fileName;

        // Move the file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Error uploading ' . ($file === $_FILES['fileFront'] ? 'front' : 'back') . ' ID.');
        }

        return $fileName; // Return the filename
    }

    // Validate and move the front file
    $frontFileName = handleFileUpload($frontFile, $uploadDir, $username, 'front');

    // Validate and move the back file
    $backFileName = handleFileUpload($backFile, $uploadDir, $username, 'back');

    error_log("Front file uploaded: " . $frontFileName);
    error_log("Back file uploaded: " . $backFileName);

    // Update the user's ID front and back in the database using PDO
    $stmt = $pdo->prepare("UPDATE tbl_user SET id_front = :id_front, id_back = :id_back WHERE id = :id");
    $stmt->bindParam(':id_front', $frontFileName);
    $stmt->bindParam(':id_back', $backFileName);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        throw new Exception('Database update failed. Error: ' . implode(', ', $stmt->errorInfo()));
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log('Error: ' . $e->getMessage()); // Log error for debugging
}

// Return the JSON response
echo json_encode($response);
?>