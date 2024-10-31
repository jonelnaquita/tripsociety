<?php
session_start();
include '../../../inc/config.php'; // Include your PDO database connection file

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure session is started and user is logged in
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit;
    }

    $user_id = $_SESSION['user']; // Get the user ID from session
    $rating = $_POST['rating'] ?? ''; // Use null coalescing operator for safety
    $app_improvement = isset($_POST['app_improvement']) ? implode(", ", $_POST['app_improvement']) : ''; // Convert array to string
    $feedback = $_POST['feedback'] ?? ''; // Use null coalescing operator for safety

    date_default_timezone_set('Asia/Manila');
    $currentTimestamp = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($rating) || empty($app_improvement)) {
        echo json_encode(['status' => 'error', 'message' => 'Rating and improvement area are required.']);
        exit;
    }

    try {
        $sql = "INSERT INTO tbl_feedback (user_id, rate, app_improvement, feedback, date_created) VALUES (:user_id, :rating, :app_improvement, :comment, :date_created)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $user_id,
            'rating' => $rating,
            'app_improvement' => $app_improvement,
            'comment' => $feedback,
            'date_created' => $currentTimestamp
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully!']);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while saving your feedback.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

?>