<?php
include '../../../inc/config.php';

// Start the session to access user data
session_start();

// Initialize user ID
$user_id = null;

// Check if user ID is provided in the URL
if (isset($_GET['user_id']) && !empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} elseif (isset($_SESSION['user'])) {
    // If no ID in URL, fall back to session user ID
    $user_id = $_SESSION['user'];
}

if (!$user_id) {
    echo json_encode(['error' => 'User not specified']);
    exit;
}

try {
    // Prepare and execute the query to fetch badges for the user
    $query = "SELECT badge, color, icon FROM tbl_badge_accomplishment WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $badges = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the result as JSON
    echo json_encode($badges);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>