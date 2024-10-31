<?php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $locationName = $_POST['location_name'];
    $locationCoords = $_POST['location'];
    $description = $_POST['description'];
    $categories = $_POST['category'];
    $city = $_POST['city-municipality'];

    $imageNames = [];
    $tourLinkPath = null;

    try {
        // Handle multiple image uploads
        if (isset($_FILES['images'])) {
            foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
                if ($_FILES['images']['error'][$index] == UPLOAD_ERR_OK) {
                    $imageName = basename($_FILES['images']['name'][$index]);
                    $imagePath = '../../images/' . $imageName;

                    // Create directory if it doesn't exist
                    if (!is_dir('../../images/')) {
                        mkdir('../../images/', 0755, true);
                    }

                    // Move each uploaded file to the target directory
                    if (move_uploaded_file($tmpName, $imagePath)) {
                        $imageNames[] = $imageName; // Add image name to array
                    } else {
                        throw new Exception('Failed to move uploaded file for image: ' . $imageName);
                    }
                }
            }
        }

        // Concatenate image names with commas
        $imageNameString = implode(',', $imageNames);

        // Handle tour link upload if available
        if (isset($_FILES['tour_link']) && $_FILES['tour_link']['error'] == UPLOAD_ERR_OK) {
            $tourLinkName = basename($_FILES['tour_link']['name']);
            $tourLinkPath = '../../panorama/' . $tourLinkName;

            if (!is_dir('../../panorama/')) {
                mkdir('../../panorama/', 0755, true);
            }

            if (!move_uploaded_file($_FILES['tour_link']['tmp_name'], $tourLinkPath)) {
                throw new Exception('Failed to move uploaded file for tour link.');
            }
        }

        // Insert location data, including image names as a comma-separated string
        $stmt = $pdo->prepare("INSERT INTO tbl_location (location_name, location, description, category, city, image, tour_link) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$locationName, $locationCoords, $description, $categories, $city, $imageNameString, $tourLinkName]);

        // Process instructions if available
        if (isset($_POST['instructions'])) {
            $locationId = $pdo->lastInsertId(); // Get last inserted ID
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