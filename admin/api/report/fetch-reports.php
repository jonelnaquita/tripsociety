<?php
include '../../../inc/config.php';

// Base SQL query to fetch reports
$sql = "
    SELECT DISTINCT pr.user_id AS reviewer_id,
            pr.id as report_id, 
            pr.post_id, 
            pr.category, 
            pr.violation, 
            DATE_FORMAT(pr.date_created, '%M %d, %Y %h:%i %p') AS reported_date, 
            tu.name AS reporter_name,
            u.name as user_name, 
            u.profile_img,
            p.post AS post_text, 
            p.image AS post_images, 
            p.location AS post_location,
            r.review AS review_text,
            l.location_name AS review_location_id,
            r.images AS review_images
    FROM tbl_post_report pr
    LEFT JOIN tbl_post p ON pr.post_id = p.id AND pr.category = 'Post'
    LEFT JOIN tbl_review r ON pr.post_id = r.id AND pr.category = 'Review'
    LEFT JOIN tbl_user u ON p.user_id = u.id OR r.user_id = u.id
    LEFT JOIN tbl_user tu ON pr.user_id = tu.id
    LEFT JOIN tbl_location l ON r.location_id = l.id
    WHERE pr.status = 0
";

// Check if an ID is provided in the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : null; // Set to null if not provided

// If an ID is provided, add a condition to filter by report_id
if ($id !== null) {
    $sql .= " AND pr.id = :report_id"; // Prepare to bind the parameter
}

$stmt = $pdo->prepare($sql);

// Bind the parameter only if an ID is present
if ($id !== null) {
    $stmt->bindParam(':report_id', $id, PDO::PARAM_INT);
}

// Execute the query
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process images for each review
foreach ($reviews as &$review) {
    if ($review['category'] == 'Post') {
        $review['images'] = !empty($review['post_images']) ? explode(',', $review['post_images']) : [];
    } else if ($review['category'] == 'Review') {
        $review['images'] = !empty($review['review_images']) ? explode(',', $review['review_images']) : [];
    }
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($reviews);
?>