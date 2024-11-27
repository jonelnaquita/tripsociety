<?php
// Include database connection
include '../../../inc/config.php';

try {
    // Calculate the average hazard rating for each destination
    $sql = "SELECT tl.location_name, 
                   AVG(
                       CASE 
                           WHEN tr.hazard_rating BETWEEN 0.0 AND 0.49 THEN 0 
                           WHEN tr.hazard_rating BETWEEN 0.5 AND 1.49 THEN 20
                           WHEN tr.hazard_rating BETWEEN 1.5 AND 2.49 THEN 40
                           WHEN tr.hazard_rating BETWEEN 2.5 AND 3.49 THEN 60 
                           WHEN tr.hazard_rating BETWEEN 3.5 AND 4.49 THEN 80 
                           WHEN tr.hazard_rating BETWEEN 4.5 AND 5.0 THEN 100 
                           ELSE NULL 
                       END
                   ) AS avg_hazard
            FROM tbl_review tr
            LEFT JOIN tbl_location tl ON tr.location_id = tl.id
            GROUP BY tl.location_name
            ORDER BY avg_hazard DESC
            LIMIT 5";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>