<?php
// Include the database configuration file
include '../../../inc/config.php';

try {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // Prepare the SQL statement
        $stmt = $pdo->prepare("SELECT location FROM tbl_location WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $location = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($location && !empty($location['location'])) {
            // Separate latitude and longitude from the location string
            list($latitude, $longitude) = explode(',', $location['location']);

            // Return the result as a JSON object
            echo json_encode(['latitude' => $latitude, 'longitude' => $longitude]);
        } else {
            // Return an error if no data found
            echo json_encode(['error' => 'Location not found']);
        }
    } else {
        echo json_encode(['error' => 'No ID provided']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection error: ' . $e->getMessage()]);
}
