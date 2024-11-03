<?php
include '../../../vendor/autoload.php';
include '../../../inc/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($email, $userId)
{
    // Create a new instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tripsociety0@gmail.com';
        $mail->Password = 'iclj sfzq qqtw vnqv'; // Make sure to use an app password if 2FA is enabled
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set email sender and recipient
        $mail->setFrom('tripsociety0@gmail.com', 'Trip Society');
        $mail->addAddress($email, 'User'); // You can change 'User' to a dynamic name if needed

        // Generate verification URL
        $verificationUrl = 'http://localhost/tripsociety_latest/admin/reset_password.php?id=' . urlencode($userId);

        // Content for HTML and Plain Text
        $mail->isHTML(true);
        $mail->Subject = 'Account Verification';
        $mail->Body = "
            <p>Hi Admin,</p>
            <p>Please verify your account by clicking the link below:</p>
            <p><a href=\"$verificationUrl\">Verify Your Account</a></p>
            <p>If you’re having trouble clicking the link, copy and paste it into your browser:</p>
            <p>$verificationUrl</p>";
        $mail->AltBody = "Hi Admin, Please verify your account by clicking the link: $verificationUrl";

        // Send the email
        $mail->send();
        echo json_encode(['response' => 'Success', 'message' => 'A verification email has been sent.']);
    } catch (Exception $e) {
        echo json_encode(['response' => 'Error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Get the email from the AJAX request
    $userId = 1; // You should replace this with the actual user ID, fetch from your database as needed

    sendVerificationEmail($email, $userId);
}
?>