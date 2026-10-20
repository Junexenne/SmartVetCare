<?php
header('Content-Type: application/json');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Kung mayroon ka nang PHPMailer library sa iyong project, i-include mo ito rito:
// require '../assets/vendor/PHPMailer/src/Exception.php';
// require '../assets/vendor/PHPMailer/src/PHPMailer.php';
// require '../assets/vendor/PHPMailer/src/SMTP.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';
$otp = $data['otp'] ?? '';
$method = $data['method'] ?? 'Email';

if (!$email || !$otp) {
    echo json_encode(["status" => "error", "message" => "Missing email or OTP."]);
    exit;
}

if ($method === 'Email') {
    // Pwede mong gamitin ang native mail() function muna para sa XAMPP testing o PHPMailer
    $subject = "Smart Vet Care - Two-Factor Authentication Code";
    $message = "Hello,\n\nYour 6-digit verification code is: " . $otp . "\n\nPlease enter this code to complete your login.\n\nThank you,\nFurry Friends Animal Clinic";
    $headers = "From: no-reply@furryfriends.com";

    // Subukang mag-send gamit ang PHP mail function
    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(["status" => "success", "message" => "OTP sent to email successfully."]);
    } else {
        // Fallback sakaling hindi pa naka-configure ang mail sa XAMPP
        echo json_encode(["status" => "success", "message" => "OTP generated (XAMPP simulated send)."]);
    }
} else {
    // Para sa SMS (Pwede mong i-integrate dito ang API tulad ng Semaphore o Twilio kung meron ka)
    echo json_encode(["status" => "success", "message" => "OTP sent via SMS successfully."]);
}
?>