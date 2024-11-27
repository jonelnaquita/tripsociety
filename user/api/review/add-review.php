<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    include '../../../inc/config.php'; // Include your database connection file
    header('Content-Type: application/json');

    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit;
    }

    $userId = $_SESSION['user'];
    $locationId = $_POST['location_id'];
    $averageRating = $_POST['average_rating'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];
    $hazardLevels = $_POST['hazard_levels'];
    $uploadDir = '../../../admin/review_image/';
    $imagePaths = [];

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $filename) {
            $filePath = $uploadDir . basename($filename);
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $filePath)) {
                $imagePaths[] = $filename;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image: ' . $filename]);
                exit;
            }
        }
    }

    $images = implode(',', $imagePaths);
    $currentTimestamp = date('Y-m-d H:i:s');

    // Determine the hazard level
    $hazard = null;
    if ($averageRating >= 0.0 && $averageRating <= 0.49) {
        $hazard = 'No Hazard'; // No Hazard
    } elseif ($averageRating >= 0.5 && $averageRating <= 1.49) {
        $hazard = 'Very Low Hazard'; // Very Low
    } elseif ($averageRating >= 1.5 && $averageRating <= 2.49) {
        $hazard = 'Low Hazard'; // Low
    } elseif ($averageRating >= 2.5 && $averageRating <= 3.49) {
        $hazard = 'Moderate Hazard'; // Moderate
    } elseif ($averageRating >= 3.5 && $averageRating <= 4.49) {
        $hazard = 'High Hazard'; // High
    } elseif ($averageRating >= 4.5 && $averageRating <= 5.0) {
        $hazard = 'Extreme Hazard'; // Extreme
    }

    try {
        // Check if a review already exists
        $checkStmt = $pdo->prepare("SELECT id FROM tbl_review WHERE user_id = :user_id AND location_id = :location_id");
        $checkStmt->execute([
            ':user_id' => $userId,
            ':location_id' => $locationId,
        ]);
        $existingReview = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingReview) {
            // Update the existing review
            $updateQuery = "
        UPDATE tbl_review
        SET rating = :rating, hazard_rating = :hazard_rating, hazard = :hazard, 
            hazard_level = :hazard_level, review = :review, date_created = :date_created";

            // Append images to the query only if new images are uploaded
            if (!empty($images)) {
                $updateQuery .= ", images = :images";
            }

            $updateQuery .= " WHERE id = :id";

            $updateStmt = $pdo->prepare($updateQuery);

            // Bind common parameters
            $updateParams = [
                ':rating' => $rating,
                ':hazard_rating' => $averageRating,
                ':hazard' => $hazard,
                ':hazard_level' => $hazardLevels, // Save the comma-separated values
                ':review' => $review,
                ':date_created' => $currentTimestamp,
                ':id' => $existingReview['id'],
            ];

            // Add images to parameters if present
            if (!empty($images)) {
                $updateParams[':images'] = $images;
            }

            $updateStmt->execute($updateParams);

            echo json_encode(['status' => 'success', 'message' => 'Review updated successfully!']);
        } else {
            // Insert a new review
            $insertStmt = $pdo->prepare("
        INSERT INTO tbl_review (user_id, location_id, rating, hazard_rating, hazard, hazard_level, review, images, date_created)
        VALUES (:user_id, :location_id, :rating, :hazard_rating, :hazard, :hazard_level, :review, :images, :date_created)
    ");
            $insertStmt->execute([
                ':user_id' => $userId,
                ':location_id' => $locationId,
                ':rating' => $rating,
                ':hazard_rating' => $averageRating,
                ':hazard' => $hazard,
                ':hazard_level' => $hazardLevels, // Save the comma-separated values
                ':review' => $review,
                ':images' => $images,
                ':date_created' => $currentTimestamp,
            ]);
            echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>