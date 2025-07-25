<?php
require_once '../config.php';

header('Content-Type: application/json');

$camera_id = $_POST['camera_id'] ?? null;
$token = $_POST['token'] ?? null;

if (!$camera_id || !$token) {
    http_response_code(400);
    echo json_encode(["error" => "camera_id and token are required"]);
    exit;
}

$ch = curl_init("$API_BASE_URL/capture?camera_id=$camera_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
echo $response;
