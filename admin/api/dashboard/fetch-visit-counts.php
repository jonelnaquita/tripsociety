<?php
// Include database connection
include '../../../inc/config.php';


try {
    $sql = "SELECT tl.location_name, COUNT(tr.location_id) as visit_count
            FROM tbl_travel_log tr
            LEFT JOIN tbl_location tl ON tr.location_id = tl.id
            GROUP BY tl.location_name
            ORDER BY visit_count DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>