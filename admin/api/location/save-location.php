<?php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get main location data
    $locationName = $_POST['location_name'];
    $locationCoords = $_POST['location'];
    $description = $_POST['description'];
    $categories = $_POST['category'];
    $city = $_POST['city-municipality'];

    // Initialize variables for image and tour link paths
    $imagePath = null;
    $tourLinkPath = null;

    try {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $imageName = basename($_FILES['image']['name']); // Get the original filename
            $imagePath = '../../images/' . $imageName; // Construct the path with original filename

            // Create directory if it doesn't exist
            if (!is_dir('../../images/')) {
                mkdir('../../images/', 0755, true);
            }

            // Move the uploaded file to the specified path
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                throw new Exception('Failed to move uploaded file for image.');
            }
        }

        // Handle tour link upload
        if (isset($_FILES['tour_link']) && $_FILES['tour_link']['error'] == UPLOAD_ERR_OK) {
            $tourLinkName = basename($_FILES['tour_link']['name']); // Get the original filename
            $tourLinkPath = '../../panorama/' . $tourLinkName; // Construct the path with original filename

            // Create directory if it doesn't exist
            if (!is_dir('../../panorama/')) {
                mkdir('../../panorama/', 0755, true);
            }

            // Move the uploaded file to the specified path
            if (!move_uploaded_file($_FILES['tour_link']['tmp_name'], $tourLinkPath)) {
                throw new Exception('Failed to move uploaded file for tour link.');
            }
        }

        // Insert into tbl_location
        $stmt = $pdo->prepare("INSERT INTO tbl_location (location_name, location, description, category, city, image, tour_link) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$locationName, $locationCoords, $description, $categories, $city, $imageName, $tourLinkName]);
        $locationId = $pdo->lastInsertId(); // Get last inserted ID

        // Process instructions
        if (isset($_POST['instructions'])) {
            foreach ($_POST['instructions'] as $instruction) {
                list($route, $details) = explode('|', $instruction);
                $stmt = $pdo->prepare("INSERT INTO tbl_instruction (location_id, location, instruction) VALUES (?, ?, ?)");
                $stmt->execute([$locationId, $route, $details]);
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Location saved successfully']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save location: ' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>