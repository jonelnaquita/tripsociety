<?php
session_start(); // Start the session if not already started
include '../../../inc/config.php';

// Fetch companions count
if (isset($_GET['fetch_companion_count'])) {
    $userId = $_SESSION['user']; // Assuming user ID is stored in the session

    $stmt = $pdo->prepare("SELECT COUNT(*) AS companion_count FROM tbl_travel_companion WHERE user_id = :user_id OR companion_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['companion_count' => $result['companion_count']]);
    exit; // Exit after sending the response
}

// Fetch reactions count
if (isset($_GET['fetch_reaction_count'])) {
    $userId = $_SESSION['user']; // Assuming user ID is stored in the session

    $stmt = $pdo->prepare("SELECT COUNT(*) AS reaction_count FROM tbl_reaction WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['reaction_count' => $result['reaction_count']]);
    exit; // Exit after sending the response
}
