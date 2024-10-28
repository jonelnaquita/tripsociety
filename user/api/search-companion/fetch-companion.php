<?php
// Database connection
include '../../../inc/config.php';

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : '';

if ($searchTerm !== '') {
    $searchTerm = "%{$searchTerm}%"; // Prepare for SQL LIKE query

    // Query the database for verified users where name or username matches the search term
    $query = $pdo->prepare("SELECT id, name, username, profile_img, status FROM tbl_user WHERE (name LIKE :searchTerm OR username LIKE :searchTerm) AND is_verified = 1");
    $query->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $query->execute();

    $users = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($users); // Send data as JSON
}
?>