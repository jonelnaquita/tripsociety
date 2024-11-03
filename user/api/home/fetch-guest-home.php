<?php
include '../../../inc/config.php'; // Make sure this path is correct

header('Content-Type: application/json'); // Set header to return JSON

// Fetch reviews
$stmt = $pdo->prepare("SELECT tr.*, tl.location_name, tu.name AS user_name, tu.profile_img FROM tbl_review tr 
                        LEFT JOIN tbl_location tl ON tl.id = tr.location_id 
                        LEFT JOIN tbl_user tu ON tu.id = tr.user_id 
                        ORDER BY tr.id DESC");
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch announcements
$stmt = $pdo->prepare("SELECT * FROM tbl_announcement ORDER BY id DESC LIMIT 2");
$stmt->execute();
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the data for output
$data = [
    'reviews' => $reviews,
    'announcements' => $announcements,
];

// Return the data as JSON
echo json_encode($data);
?>