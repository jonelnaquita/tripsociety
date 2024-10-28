<?php
include '../../../inc/config.php';

header('Content-Type: application/json');

// Get category and cities from the request
$category = isset($_GET['category']) ? $_GET['category'] : '';
$cities = isset($_GET['cities']) ? explode(',', $_GET['cities']) : [];

try {
    // Start building the SQL query
    $sql = 'SELECT l.id, l.location_name, l.image, COALESCE(AVG(r.rating), 0) AS average_rating
            FROM tbl_location l
            LEFT JOIN tbl_review r ON l.id = r.location_id
            WHERE 1=1'; // Add base condition for filtering

    // Prepare an array to hold parameters
    $params = [];

    // Filter by category if provided
    if (!empty($category)) {
        $sql .= " AND FIND_IN_SET(:category, l.category) > 0"; // Use l.category to specify the table
        $params[':category'] = $category; // Add to parameters array
    }

    // Filter by cities if provided
    if (!empty($cities)) {
        // Create named placeholders for each city
        $cityConditions = [];
        foreach ($cities as $index => $city) {
            $cityConditions[] = "l.city = :city$index"; // Create conditions for each city
            $params[":city$index"] = $city; // Add the city to parameters
        }
        // Combine city conditions with OR
        $sql .= " AND (" . implode(' OR ', $cityConditions) . ")";
    }

    // Group by to ensure accurate average ratings
    $sql .= " GROUP BY l.id, l.location_name, l.image";

    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    // Bind values using named placeholders
    foreach ($params as $placeholder => $value) {
        $stmt->bindValue($placeholder, $value);
    }

    // Execute the query
    $stmt->execute();
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the locations as JSON
    echo json_encode($locations);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>