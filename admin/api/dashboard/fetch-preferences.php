<?php
// Include database connection
include '../../../inc/config.php';

try {
    $sql = "SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(travel_preferences, ',', numbers.n), ',', -1)) AS preference,
                   COUNT(*) AS preference_count
            FROM tbl_user
            INNER JOIN (
                SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL
                SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL
                SELECT 9 UNION ALL SELECT 10
            ) numbers ON CHAR_LENGTH(travel_preferences) - CHAR_LENGTH(REPLACE(travel_preferences, ',', '')) >= numbers.n - 1
            GROUP BY preference
            ORDER BY preference_count DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>