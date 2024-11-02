<?php
include '../../../inc/config.php';

session_start();

// Check if the user is authenticated
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$userId = $_SESSION['user'];

try {
    // Query to get announcements that have not been viewed by the user
    $stmt = $pdo->prepare(
        "SELECT
            ta.id, ta.title, ta.description, ta.image, ta.date_created
        FROM tbl_announcement ta
        LEFT JOIN tbl_announcement_viewed tav ON tav.announcement_id = ta.id AND tav.user_id = :user_id
        WHERE tav.id IS NULL
        ORDER BY ta.date_created DESC
        "
    );

    // Bind the user ID parameter
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch all announcements
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through the announcements to format date_created
    foreach ($announcements as &$announcement) {
        // Convert date_created to DateTime object
        $date = new DateTime($announcement['date_created']);

        // Format the date as 'October 10, 2024 10:00 AM'
        $announcement['date_created'] = $date->format('F j, Y g:i A');
    }

    // Return as JSON
    echo json_encode($announcements);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>