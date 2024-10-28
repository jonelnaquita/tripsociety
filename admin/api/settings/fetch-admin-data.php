<?php
session_start();
include '../../../inc/config.php';

if (isset($_SESSION['admin'])) {
    $admin = $_SESSION['admin'];

    $stmt = $pdo->prepare("SELECT username, email FROM tbl_account WHERE id = :admin_id");
    $stmt->execute(['admin_id' => $admin]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['username' => '', 'email' => '']);
    }
} else {
    echo json_encode(['username' => '', 'email' => '']);
}
?>