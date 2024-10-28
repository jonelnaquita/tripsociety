<?php
include '../../../inc/config.php';

try {
    // Query to get announcements
    $stmt = $pdo->prepare("SELECT title, description, image, date_created FROM tbl_announcement ORDER BY date_created
DESC");
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