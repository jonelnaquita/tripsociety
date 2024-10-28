<?php
include '../../../inc/config.php';

try {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id) {
        // Fetch specific user by ID
        $query = "SELECT id, name, email, travel_preferences, location, profile_img, status FROM tbl_user WHERE id = :id AND is_verified = 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        // Fetch all users
        $query = "SELECT id, name, email, travel_preferences, location, profile_img, status FROM tbl_user WHERE is_verified = 1 ORDER BY name ASC";
        $stmt = $pdo->prepare($query);
    }

    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>