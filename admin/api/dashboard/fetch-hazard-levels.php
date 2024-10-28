<?php
// Include database connection
include '../../../inc/config.php';

try {
    $sql = "SELECT tl.location_name, 
                   AVG(CASE 
                       WHEN tr.hazard = 'No Hazard' THEN 0 
                       WHEN tr.hazard = 'Very Low Hazard' THEN 20
                       WHEN tr.hazard = 'Low Hazard' THEN 40
                       WHEN tr.hazard = 'Moderate Hazard' THEN 60 
                       WHEN tr.hazard = 'High Hazard' THEN 80 
                       WHEN tr.hazard = 'Extreme Hazard' THEN 100 
                       ELSE NULL 
                   END) as avg_hazard
            FROM tbl_review tr
            LEFT JOIN tbl_location tl ON tr.location_id = tl.id
            GROUP BY tl.location_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>