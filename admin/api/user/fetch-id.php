<?php
include '../../../inc/config.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare("SELECT id_front, id_back FROM tbl_user WHERE id = :id");
        $stmt->execute(['id' => $userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Return the front and back ID images as JSON
            echo json_encode($user);
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>