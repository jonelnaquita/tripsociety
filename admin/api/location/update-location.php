<?php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $locationId = $_POST['id'];
    $locationName = $_POST['location_name'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $category = $_POST['category']; // Get category as string
    $city = $_POST['city'];

    // Process uploaded files
    $imageName = null;
    $tourLinkName = null;

    // Check if image was uploaded
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] == UPLOAD_ERR_OK) {
        $imageNames = [];
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] == UPLOAD_ERR_OK) {
                $imageName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", basename($_FILES['images']['name'][$key])); // Sanitize
                $imagePath = '../../images/' . $imageName;

                // Create directory if it doesn't exist
                if (!is_dir('../../images/')) {
                    mkdir('../../images/', 0755, true);
                }

                // Move the uploaded file
                if (move_uploaded_file($tmp_name, $imagePath)) {
                    $imageNames[] = $imageName; // Keep track of uploaded image names
                }
            }
        }
        $imageNamesStr = implode(',', $imageNames); // Convert to string to save in DB
    } else {
        $imageNamesStr = null; // Handle no image uploaded case
    }

    // Handle tour link upload
    if (isset($_FILES['tour_link']) && $_FILES['tour_link']['error'] == UPLOAD_ERR_OK) {
        $tourLinkName = preg_replace("/[^a-zA-Z0-9\.\-_]/", "_", basename($_FILES['tour_link']['name'])); // Sanitize the filename
        $tourLinkPath = '../../panorama/' . $tourLinkName;

        // Create directory if it doesn't exist
        if (!is_dir('../../panorama/')) {
            mkdir('../../panorama/', 0755, true);
        }

        // Move the uploaded file to the specified path
        if (!move_uploaded_file($_FILES['tour_link']['tmp_name'], $tourLinkPath)) {
            echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file for tour link.']);
            exit();
        }
    } else {
        // No new tour link uploaded, keep existing tour link name
        $stmt = $pdo->prepare("SELECT tour_link FROM tbl_location WHERE id = :id");
        $stmt->execute(['id' => $locationId]);
        $existingTourLink = $stmt->fetchColumn();
        $tourLinkName = $existingTourLink; // Preserve existing tour link
    }

    // Update location in tbl_location
    $stmt = $pdo->prepare("UPDATE tbl_location SET 
        location_name = :location_name, location = :location, 
        description = :description, category = :category, city = :city, 
        image = :images WHERE id = :id"); // Ensure your database has an images column
    try {
        $stmt->execute([
            'location_name' => $locationName,
            'location' => $location,
            'description' => $description,
            'category' => $category,
            'city' => $city,
            'images' => $imageNamesStr, // Save the image names string
            'id' => $locationId
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Database update failed: ' . $e->getMessage()]);
        exit();
    }

    // Delete existing instructions
    $stmtDelete = $pdo->prepare("DELETE FROM tbl_instruction WHERE location_id = :location_id");
    $stmtDelete->execute(['location_id' => $locationId]);

    if (isset($_POST['instructions'])) {
        foreach ($_POST['instructions'] as $instruction) {
            list($route, $details) = explode('|', $instruction);
            $stmt = $pdo->prepare("INSERT INTO tbl_instruction (location_id, location, instruction) VALUES (?, ?, ?)");
            $stmt->execute([$locationId, $route, $details]);
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>