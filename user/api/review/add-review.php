<?php
session_start();
include '../../../inc/config.php'; // Include your PDO database connection file

header('Content-Type: application/json'); // Set content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure session is started and user is logged in
    if (!isset($_SESSION['user'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
        exit;
    }

    $id = $_POST['id'];
    $user_id = $_SESSION['user'];
    $rating = $_POST['rating'];
    $hazard = $_POST['hazard'];
    $review = $_POST['review'];
    $uploadDir = '../../../admin/review_image/';
    $imagePaths = [];

    date_default_timezone_set('Asia/Manila');
    $currentTimestamp = date('Y-m-d H:i:s');

    // Validate required fields
    if (empty($rating) || empty($hazard)) {
        echo json_encode(['status' => 'error', 'message' => 'Rating, hazard level, and review are required.']);
        exit;
    }

    if (!empty($_FILES['images']['name'][0])) {
        $fileCount = count($_FILES['images']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = basename($_FILES['images']['name'][$i]);
            $targetFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFilePath)) {
                $imagePaths[] = $fileName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload image: ' . $_FILES['images']['name'][$i]]);
                exit;
            }
        }
    }
    $images = implode(',', $imagePaths);

    try {
        $sql = "INSERT INTO tbl_review (user_id, location_id, rating, hazard, review, images, date_created) VALUES (:user_id, :location_id, :rating, :hazard, :review, :images, :date_created)";
        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'user_id' => $user_id,
            'location_id' => $id,
            'rating' => $rating,
            'hazard' => $hazard,
            'review' => $review,
            'images' => $images,
            'date_created' => $currentTimestamp
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Review submitted successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>