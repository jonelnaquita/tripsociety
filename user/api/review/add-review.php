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
    $review = $_POST['review'];
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
        $hazard = 'Very Low'; // Very Low
    } elseif ($averageRating >= 1.5 && $averageRating <= 2.49) {
        $hazard = 'Low'; // Low
    } elseif ($averageRating >= 2.5 && $averageRating <= 3.49) {
        $hazard = 'Moderate'; // Moderate
    } elseif ($averageRating >= 3.5 && $averageRating <= 4.49) {
        $hazard = 'High'; // High
    } elseif ($averageRating >= 4.5 && $averageRating <= 5.0) {
        $hazard = 'Extreme'; // Extreme
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO tbl_review (user_id, location_id, rating, hazard, review, images, date_created)
            VALUES (:user_id, :location_id, :rating, :hazard, :review, :images, :date_created)
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':location_id' => $locationId,
            ':rating' => $averageRating,
            ':hazard' => $hazard,
            ':review' => $review,
            ':images' => $images,
            ':date_created' => $currentTimestamp,
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>