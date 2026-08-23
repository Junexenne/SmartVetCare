<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['userId'])) {
    $_SESSION['user_id'] = $data['userId'];
    echo json_encode(["status" => "success"]);
} else {
    http_response_code(400);
}
?>