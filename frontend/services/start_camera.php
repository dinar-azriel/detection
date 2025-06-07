<?php
require_once '../config.php';

header('Content-Type: application/json');

$camera_id = $_POST['camera_id'] ?? null;

if ($camera_id === null) {
    http_response_code(400);
    echo json_encode(["error" => "camera_id is required"]);
    exit;
}

$ch = curl_init("$API_BASE_URL/start_camera?camera_id=$camera_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
echo $response;
