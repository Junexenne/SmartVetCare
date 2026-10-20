<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$otp = $data['otp'] ?? '';

if (!$email || !$otp) {
    echo json_encode(["status" => "error", "message" => "Missing email or OTP."]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings para sa Gmail SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'junexennea@gmail.com';
    $mail->Password   = 'iwgpqcfehinvbmia';
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('junexennea@gmail.com', 'Furry Friends Animal Clinic');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Smart Vet Care - Two-Factor Authentication Code';
    $mail->Body    = "
        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
            <h2 style='color: #173F81;'>Smart Vet Care - 2FA Verification</h2>
            <p>Hello,</p>
            <p>Your 6-digit verification code for login is:</p>
            <div style='font-size: 28px; font-weight: bold; color: #5142f5; letter-spacing: 5px; padding: 10px; background: #f1f5f9; text-align: center; border-radius: 8px; width: 200px; margin: 15px 0;'>
                {$otp}
            </div>
            <p style='color: #64748b; font-size: 12px;'>If you did not request this, please ignore this email.</p>
        </div>
    ";
    $mail->AltText = "Your 6-digit verification code is: " . $otp;

    $mail->send();
    echo json_encode(["status" => "success", "message" => "OTP sent successfully via Gmail SMTP."]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
}
?>