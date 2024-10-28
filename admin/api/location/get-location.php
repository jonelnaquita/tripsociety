<?php
include '../../../inc/config.php';

if (isset($_GET['id'])) {
    $locationId = $_GET['id'];

    // Get location details
    $stmt = $pdo->prepare("SELECT * FROM tbl_location WHERE id = :id");
    $stmt->execute(['id' => $locationId]);
    $location = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get instructions for the location
    $stmtInstructions = $pdo->prepare("SELECT * FROM tbl_instruction WHERE location_id = :location_id");
    $stmtInstructions->execute(['location_id' => $locationId]);
    $instructions = $stmtInstructions->fetchAll(PDO::FETCH_ASSOC);

    // Convert category string to an array
    $categories = explode(', ', $location['category']);

    // Response format
    $response = [
        'location_name' => $location['location_name'],
        'location' => $location['location'],
        'description' => $location['description'],
        'category' => $categories,  // Send as an array
        'city' => $location['city'],
        'image' => $location['image'],
        'virtual_tour' => $location['tour_link'],
        'instructions' => $instructions // Instructions data
    ];

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Location ID not provided.']);
}
?>