<?php
session_start();
include '../../../inc/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
        echo json_encode(["status" => "error", "message" => "User not logged in."]);
        exit;
    }

    $userId = $_SESSION['user'];
    $locationId = filter_input(INPUT_POST, 'location_id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($locationId)) {
        echo json_encode(["status" => "error", "message" => "Invalid location ID."]);
        exit;
    }

    try {
        // Prepare and execute the query
        $sql = "SELECT * FROM tbl_review WHERE user_id = :user_id AND location_id = :location_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':location_id', $locationId, PDO::PARAM_INT);
        $stmt->execute();

        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($review) {
            // Split hazard_level into q1 to q5
            $hazardLevels = explode(',', $review['hazard_level']);
            $images = explode(',', $review['images']); // Split images into an array

            $response = [
                "status" => "found",
                "id" => $review['id'], // Include review ID
                "rating" => $review['rating'],
                "q1" => $hazardLevels[0] ?? null,
                "q2" => $hazardLevels[1] ?? null,
                "q3" => $hazardLevels[2] ?? null,
                "q4" => $hazardLevels[3] ?? null,
                "q5" => $hazardLevels[4] ?? null,
                "review" => $review['review'],
                "images" => $images // Return images as an array
            ];

            echo json_encode($response);
        } else {
            echo json_encode(["status" => "not_found"]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Internal Server Error: " . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>