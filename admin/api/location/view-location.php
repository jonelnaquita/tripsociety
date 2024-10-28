<?php
// fetch-location.php
include '../../../inc/config.php';

if (isset($_GET['id'])) {
    $locationId = (int) $_GET['id']; // Ensure the ID is an integer to prevent SQL injection

    // Prepare a SQL query to fetch location and related instructions
    $stmt = $pdo->prepare("
        SELECT 
            loc.location_name, loc.image, loc.description, loc.category, loc.city,
            instr.location, instr.location as route_text, instr.instruction as instruction_text
        FROM 
            tbl_location loc
        LEFT JOIN 
            tbl_instruction instr ON loc.id = instr.location_id
        WHERE 
            loc.id = :id
    ");
    $stmt->bindParam(':id', $locationId, PDO::PARAM_INT);

    $stmt->execute();

    // Fetch the location data
    $location = $stmt->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll to get multiple rows

    // Check if location exists
    if (!empty($location)) {
        // Organize the data
        $result = [
            'location_name' => $location[0]['location_name'],
            'image' => $location[0]['image'],
            'description' => $location[0]['description'],
            'category' => $location[0]['category'],
            'city' => $location[0]['city'],
            'instructions' => []
        ];

        // Loop through the results to gather instruction texts
        foreach ($location as $row) {
            if (!empty($row['route_text']) && !empty($row['instruction_text'])) {
                $result['instructions'][] = [
                    'route_text' => $row['route_text'],
                    'instruction_text' => $row['instruction_text']
                ];
            }
        }

        echo json_encode($result); // Return data as JSON
    } else {
        echo json_encode(['error' => 'Location not found']);
    }
} else {
    echo json_encode(['error' => 'No ID provided']);
}
?>