<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../../../inc/config.php';
require '../../../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the email from the request
$email = $_POST['email-reset'];

try {
    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a reset token
        $resetToken = bin2hex(random_bytes(16)); // Example token generation
        $stmt = $pdo->prepare("UPDATE tbl_user SET reset_token = :reset_token WHERE email = :email");
        $stmt->execute(['reset_token' => $resetToken, 'email' => $email]);

        // Prepare to send email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tripsociety0@gmail.com'; // Your SMTP username
        $mail->Password = 'iclj sfzq qqtw vnqv'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tripsociety0@gmail.com', 'Trip Society');
        $mail->addAddress($email);

        // Prepare the email content
        $resetUrl = "https://tripsociety.net/user/reset-password.php?token=$resetToken";

        // Load the email template and replace placeholders
        $emailBody = file_get_contents('forgot-password-email.php');
        $emailBody = str_replace('{{action_url}}', $resetUrl, $emailBody);
        $emailBody = str_replace('{{name}}', $user['name'], $emailBody); // Use the user's name

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';
        $mail->Body = $emailBody;
        $mail->AltBody = "Hi {$user['name']}, reset your password by clicking this link: $resetUrl";

        // Send the email
        $mail->send();
        echo json_encode(['response' => 'Success', 'message' => 'A reset password email has been sent.']);
    } else {
        echo json_encode(['response' => 'Error', 'message' => 'Email address not found.']);
    }
} catch (Exception $e) {
    echo json_encode(['response' => 'Error', 'message' => 'Could not send email: ' . $e->getMessage()]);
}
?>