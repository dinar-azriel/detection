<?php
session_start();
require_once '../config.php';

$camera_id = $_POST['camera_id'];
$room_name = $_POST['room_name'];

$payload = json_encode([
  "camera_id" => (int)$camera_id,
  "room_name" => $room_name
]);

$ch = curl_init("{$API_BASE_URL}/register_camera");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
  $_SESSION['camera_msg'] = "Kamera berhasil ditambahkan.";
} else {
  $_SESSION['camera_msg'] = "Gagal menambahkan kamera.";
}
header("Location: ../views/cameras.php");
