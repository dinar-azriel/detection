<?php
require_once '../config.php';
header("Content-Type: application/json");

$room = $_GET['room'] ?? null;

try {
  if ($room) {
    $stmt = $pdo->prepare("SELECT timestamp, room_name, person_count, image_path FROM detections WHERE room_name = :room ORDER BY timestamp DESC");
    $stmt->execute(['room' => $room]);
  } else {
    $stmt = $pdo->query("SELECT timestamp, room_name, person_count, image_path FROM detections ORDER BY timestamp DESC");
  }

  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($rows);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["error" => "Gagal mengambil data: " . $e->getMessage()]);
}
