<?php
include '../../../inc/config.php';

$sql = 'SELECT l.id, l.location_name, l.image, COALESCE(AVG(r.rating), 0) AS average_rating
        FROM tbl_location l
        LEFT JOIN tbl_review r ON l.id = r.location_id
        GROUP BY l.id, l.location_name, l.image';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return data as JSON
echo json_encode($locations);
?>