<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$inputData = json_decode(file_get_contents('php://input'), true);

if (!empty($inputData['user_id'])) {
    $_SESSION['user_id'] = $inputData['user_id'];
    $_SESSION['owner_id'] = $inputData['user_id'];
    $_SESSION['uid'] = $inputData['user_id'];
    
    $_SESSION['email'] = $inputData['email'] ?? '';
    $_SESSION['role'] = $inputData['role'] ?? '';
}
?>