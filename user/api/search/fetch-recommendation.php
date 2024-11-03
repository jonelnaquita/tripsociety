<?php
include '../../../inc/config.php';

// Get the user_id from the AJAX request (replace this with actual session or authentication mechanism)
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

if ($user_id) {
    // Fetch user preferences
    $sql = 'SELECT travel_preferences FROM tbl_user WHERE id = :user_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $preferences = array_map('trim', explode(',', $user['travel_preferences']));

        if (!empty($preferences)) {
            // Prepare SQL query to match against categories
            $conditions = [];
            foreach ($preferences as $index => $preference) {
                $conditions[] = "FIND_IN_SET(:preference_$index, category) > 0";
            }
            $whereClause = implode(' OR ', $conditions);

            // SQL to fetch matching locations
            $sqlLocations = "SELECT id, location_name, image 
                             FROM tbl_location 
                             WHERE $whereClause";
            $stmtLocations = $pdo->prepare($sqlLocations);

            // Bind each preference value to the SQL statement
            foreach ($preferences as $index => $preference) {
                $stmtLocations->bindValue(":preference_$index", $preference);
            }

            $stmtLocations->execute();
            $locations = $stmtLocations->fetchAll(PDO::FETCH_ASSOC);

            // Return the locations in JSON format
            echo json_encode($locations);
        } else {
            echo json_encode(['error' => 'No preferences found']);
        }
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} else {
    // If user ID is not set, show 5 random locations
    $sqlRandomLocations = "SELECT id, location_name, image 
                           FROM tbl_location 
                           ORDER BY RAND() 
                           LIMIT 6";
    $stmtRandomLocations = $pdo->prepare($sqlRandomLocations);
    $stmtRandomLocations->execute();
    $randomLocations = $stmtRandomLocations->fetchAll(PDO::FETCH_ASSOC);

    // Return the random locations in JSON format
    echo json_encode($randomLocations);
}
?>