<?php
require_once '../config.php';

header('Content-Type: application/json');

$token = $_GET['token'] ?? null;

if (!$token) {
    http_response_code(400);
    echo json_encode(["error" => "Token is required"]);
    exit;
}

$ch = curl_init("$API_BASE_URL/camera_list");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
echo $response;
