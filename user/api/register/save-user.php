<?php
session_start();
include '../../../inc/config.php';
require '../../../vendor/autoload.php'; // Include PHPMailer using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['name'], $_POST['email'], $_POST['username'], $_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $profile_img = 'default.jpg';
    $cover_img = 'cover.jpg';

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $verificationToken = bin2hex(random_bytes(16)); // Generate a random verification token

    try {
        // Check if username already exists
        $checkUsernameSql = "SELECT COUNT(*) FROM tbl_user WHERE username = :username";
        $stmt = $pdo->prepare($checkUsernameSql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $usernameExists = $stmt->fetchColumn();

        // Check if email already exists
        $checkEmailSql = "SELECT COUNT(*) FROM tbl_user WHERE email = :email";
        $stmt = $pdo->prepare($checkEmailSql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        // If username or email exists, return error
        if ($usernameExists || $emailExists) {
            echo json_encode(['response' => 'Error', 'message' => 'Username or Email already exists.']);
            exit;
        }

        // Insert new user data into the database with the verification token
        $insertSql = "INSERT INTO tbl_user (name, email, username, password, profile_img, cover_img, verification_token, is_verified) 
                      VALUES (:name, :email, :username, :password, :profile_img, :cover_img, :verification_token, 0)";
        $stmt = $pdo->prepare($insertSql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':profile_img', $profile_img, PDO::PARAM_STR);
        $stmt->bindParam(':cover_img', $cover_img, PDO::PARAM_STR);
        $stmt->bindParam(':verification_token', $verificationToken, PDO::PARAM_STR);
        $stmt->execute();

        // Send verification email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $SMTPEMAIL;
            $mail->Password = $SMTPPASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($SMTPEMAIL, 'Trip Society');
            $mail->addAddress($email, $name);

            // Email content
            $verificationUrl = $WEBSITEURL . '/user/verify.php?token=' . $verificationToken;

            // Load the email template and replace placeholders with actual data
            $emailBody = file_get_contents('verify-email.php'); // Load the HTML file
            $emailBody = str_replace('{{action_url}}', $verificationUrl, $emailBody); // Replace placeholder URL
            $emailBody = str_replace('{{name}}', $name, $emailBody); // Optionally replace name if needed

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Account Verification';
            $mail->Body = $emailBody;
            $mail->AltBody = "Hi $name, Please verify your account by clicking the link: $verificationUrl";

            $mail->send();
            echo json_encode(['response' => 'Success', 'message' => 'A verification email has been sent.']);
        } catch (Exception $e) {
            echo json_encode(['response' => 'Error', 'message' => 'Verification email could not be sent.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['response' => 'Error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['response' => 'Error', 'message' => 'All fields are required.']);
}
?>