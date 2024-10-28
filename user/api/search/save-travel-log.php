<?php
session_start();
include '../../../inc/config.php'; // Include the database config

if (isset($_POST['current_lat']) && isset($_POST['current_lng'])) {
    $user_id = $_SESSION['user'];
    $current_lat = $_POST['current_lat'];
    $current_lng = $_POST['current_lng'];

    try {
        // Fetch location data from tbl_location
        $stmt = $pdo->prepare("SELECT id, location FROM tbl_location");
        $stmt->execute();
        $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($locations) {
            // Haversine formula function
            function haversine($lat1, $lon1, $lat2, $lon2)
            {
                $earth_radius = 6371000; // radius of Earth in meters
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $distance = $earth_radius * $c; // Distance in meters
                return $distance;
            }

            // Loop through locations and check proximity
            foreach ($locations as $location) {
                list($location_lat, $location_lng) = explode(',', $location['location']);
                $distance = haversine($current_lat, $current_lng, $location_lat, $location_lng);

                if ($distance <= 500) {
                    // Insert data into tbl_travel_log if within 500 meters
                    $insert = $pdo->prepare("INSERT INTO tbl_travel_log (user_id, location_id, coordinates) VALUES (:user_id, :location_id, :coordinates)");
                    $coordinates = $current_lat . ',' . $current_lng;
                    $insert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                    $insert->bindParam(':location_id', $location['id'], PDO::PARAM_INT);
                    $insert->bindParam(':coordinates', $coordinates, PDO::PARAM_STR);
                    $insert->execute();

                    echo json_encode(['status' => 'success', 'message' => 'Travel log inserted successfully']);
                    return; // Exit after the first match
                }
            }

            // If no location is within 500 meters
            echo json_encode(['status' => 'error', 'message' => 'User is not within 500 meters of any location']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No locations found']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
}
