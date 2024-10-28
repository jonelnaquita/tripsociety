<?php
// fetch_recent_searches.php
include '../../../inc/config.php';

$query = "
    SELECT DISTINCT u.location_id, l.location_name 
    FROM tbl_user_searches u
    JOIN tbl_location l ON u.location_id = l.id
    ORDER BY u.date_created DESC
    LIMIT 4
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the data in JSON format
echo json_encode($results);
?>