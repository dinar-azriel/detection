<?php
require_once '../config.php';

header('Content-Type: application/json');

$camera_id = $_POST['camera_id'] ?? null;
$room_name = $_POST['room_name'] ?? null;
$token = $_POST['token'] ?? null;

if (!$camera_id || !$room_name || !$token) {
    http_response_code(400);
    echo json_encode(["error" => "camera_id, room_name, and token are required"]);
    exit;
}

$data = [
    'camera_id' => (int)$camera_id,
    'room_name' => $room_name
];

$ch = curl_init("$API_BASE_URL/register_camera");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
echo $response;
