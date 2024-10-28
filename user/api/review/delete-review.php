<?php
// delete_review.php
include '../../../inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tbl_review WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo 'success';
        } else {
            echo 'error'; // No rows deleted, maybe ID doesn't exist
        }
    } catch (PDOException $e) {
        echo 'error'; // Handle any error that occurs
    }
}
?>