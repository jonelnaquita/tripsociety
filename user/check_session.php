<?php
session_start();
include '../inc/config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['session_login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Session expired']);
    exit;
}

$code = $_SESSION['session_login'];

try {

    $stmt = $pdo->prepare('SELECT * FROM tbl_account WHERE session_login = :code');
    $stmt->execute(['code' => $code]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'not_found']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
