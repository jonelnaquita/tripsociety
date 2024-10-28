<?php
session_start();
include_once "../inc/config.php";

if (isset($_POST['outgoing_id']) && isset($_POST['incoming_id']) && isset($_POST['message'])) {
    $outgoingId = $_POST['outgoing_id'];
    $incomingId = $_POST['incoming_id'];
    $message = $_POST['message'];

    try {
        $sql = "INSERT INTO tbl_message (sender_id, receiver_id, message) VALUES (:outgoing_id, :incoming_id, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':outgoing_id', $outgoingId, PDO::PARAM_INT);
        $stmt->bindParam(':incoming_id', $incomingId, PDO::PARAM_INT);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->execute();
        echo 'Message sent successfully';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
