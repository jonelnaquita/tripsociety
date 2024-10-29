<?php
session_start();
include_once "../inc/config.php";

if (isset($_POST['outgoing_id']) && isset($_POST['incoming_id']) && isset($_POST['message'])) {
    date_default_timezone_set('Asia/Manila'); // Set timezone to Asia/Manila
    $outgoingId = $_POST['outgoing_id'];
    $incomingId = $_POST['incoming_id'];
    $message = $_POST['message'];
    $dateCreated = date('Y-m-d H:i:s'); // Get current date and time

    try {
        // Include date_created in the INSERT statement
        $sql = "INSERT INTO tbl_message (sender_id, receiver_id, message, date_created) 
                VALUES (:outgoing_id, :incoming_id, :message, :date_created)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':outgoing_id', $outgoingId, PDO::PARAM_INT);
        $stmt->bindParam(':incoming_id', $incomingId, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':date_created', $dateCreated, PDO::PARAM_STR); // Bind date_created
        $stmt->execute();
        echo 'Message sent successfully';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>