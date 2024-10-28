<?php 
session_start();
include 'inc/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $email = $_SESSION['verified_email'];
    $verification_code = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM tbl_account WHERE email = :email AND verification_code = :verification_code");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            echo "Verification code correct!";
        } else {
            echo "Verification code does not match.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>