<?php
session_start();
include '../../../inc/config.php';

if (isset($_POST['companion_id'])) {
    $user_id = $_SESSION['user']; // Assuming user_id is stored in the session
    $companion_id = $_POST['companion_id'];
    $status = "Requesting";

    try {
        $sql = "INSERT INTO tbl_travel_companion (user_id, companion_id, status) VALUES (:user_id, :companion_id, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':companion_id' => $companion_id,
            ':status' => $status
        ]);

        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>