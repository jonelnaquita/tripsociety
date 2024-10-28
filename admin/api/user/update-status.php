<?php
// update-status.php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    if (isset($id) && isset($status)) {
        try {
            $stmt = $pdo->prepare("UPDATE tbl_user SET status = :status WHERE id = :id");
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'User status updated successfully.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Failed to update user status: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>