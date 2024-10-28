<?php
// Include database connection
include '../../../inc/config.php';

try {
    $sql = "SELECT location, COUNT(*) as user_count FROM tbl_user GROUP BY location";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>