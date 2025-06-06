<?php
require_once '../config.php';
$camera_id = $_GET['camera_id'] ?? null;

if (!$camera_id) {
  http_response_code(400);
  echo json_encode(["error" => "camera_id diperlukan"]);
  exit;
}

$ch = curl_init("{$API_BASE_URL}/capture?camera_id={$camera_id}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header("Content-Type: application/json");
http_response_code($http_code);
echo $response;
