<?php
include '../../../inc/config.php';

try {

    // Get the location_id from the URL
    if (isset($_GET['location_id'])) {
        $location_id = $_GET['location_id'];

        // Prepare the SQL statement
        $stmt = $pdo->prepare("SELECT location, instruction FROM tbl_instruction WHERE location_id = :location_id");
        $stmt->bindParam(':location_id', $location_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the results
        $instructions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results as JSON
        echo json_encode($instructions);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>