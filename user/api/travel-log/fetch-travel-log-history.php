<?php
include '../../../inc/config.php'; // Include your database configuration

// Assuming you have the user_id stored in the session
session_start();
$user_id = $_SESSION['user'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

try {
    // Query to group by location_id and date (day, month, year)
    $query = "
        SELECT l.location_name, DATE(t.date_created) as travel_date, COUNT(t.id) as visit_count
        FROM tbl_travel_log t
        JOIN tbl_location l ON t.location_id = l.id
        WHERE t.user_id = :user_id
        GROUP BY t.location_id, travel_date
        ORDER BY t.date_created ASC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $travel_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize array to hold processed logs
    $log_data = [];

    foreach ($travel_logs as $log) {
        // Format the travel_date in 'F j, Y' format (September 10, 2024)
        $formatted_date = date('F j, Y', strtotime($log['travel_date']));

        $log_data[] = [
            'location_name' => $log['location_name'],
            'formatted_date' => $formatted_date,
            'visit_count' => $log['visit_count']
        ];
    }

    echo json_encode($log_data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
