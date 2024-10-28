<?php
// Include database connection
include '../../../inc/config.php';

try {
    // Total users count
    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalUsers FROM tbl_user");
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['totalUsers'];

    // Verified users count (status = 1)
    $stmt = $pdo->prepare("SELECT COUNT(*) AS verifiedUsers FROM tbl_user WHERE status = '1'");
    $stmt->execute();
    $verifiedUsers = $stmt->fetch(PDO::FETCH_ASSOC)['verifiedUsers'];

    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalDestination FROM tbl_location");
    $stmt->execute();
    $totalDestination = $stmt->fetch(PDO::FETCH_ASSOC)['totalDestination'];

    $stmt = $pdo->prepare("SELECT COUNT(*) AS totalReports FROM tbl_post_report");
    $stmt->execute();
    $totalReports = $stmt->fetch(PDO::FETCH_ASSOC)['totalReports'];

    // Send data as JSON response
    echo json_encode([
        'totalUsers' => $totalUsers,
        'verifiedUsers' => $verifiedUsers,
        'totalDestination' => $totalDestination,
        'totalReports' => $totalReports

    ]);
} catch (PDOException $e) {
    // Handle error and send response
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
    exit();
}
?>