<?php
// fetch-feedback.php

include '../../../inc/config.php';
session_start();

if (isset($_SESSION['user'])) {
    $id = $_SESSION['user'];

    // Modify the query to filter feedback by the current user's user_id
    $query = "SELECT tf.*, tu.id AS user_id, tu.name, tu.profile_img, tu.status, tfr.message AS admin_response
              FROM tbl_feedback tf
              LEFT JOIN tbl_user tu ON tu.id = tf.user_id
              LEFT JOIN tbl_feedback_respond tfr ON tf.id = tfr.feedback_id
              WHERE tf.user_id = :user_id
              ORDER BY tf.id DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([':user_id' => $id]);
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode($locations);
}
?>