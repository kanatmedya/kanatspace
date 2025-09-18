<?php
$data = json_decode(file_get_contents("php://input"), true);
$token = $data['token'] ?? '';

if (!empty($token)) {
    file_put_contents("tokens.txt", $token . PHP_EOL, FILE_APPEND);
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}
?>
