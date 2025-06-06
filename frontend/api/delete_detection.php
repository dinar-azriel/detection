<?php
require_once '../config.php';
$timestamp = $_GET['timestamp'] ?? null;

if (!$timestamp) {
  http_response_code(400);
  echo json_encode(["error" => "timestamp diperlukan"]);
  exit;
}

$stmt = $pdo->prepare("DELETE FROM detections WHERE timestamp = :timestamp");
$stmt->execute(['timestamp' => $timestamp]);

echo json_encode(["success" => true]);
