<?php
// stop_camera.php

$data = json_decode(file_get_contents('php://input'), true);
$camera_id = $data['camera_id'] ?? '';

if ($camera_id === '') {
  http_response_code(400);
  echo json_encode(['error' => 'Camera ID kosong']);
  exit;
}

$backend_url = 'http://localhost:8000/stop_camera?camera_id=' . urlencode($camera_id);

$options = [
  'http' => [
    'method'  => 'POST',
  ]
];

$context  = stream_context_create($options);
$result = file_get_contents($backend_url, false, $context);

if ($result === FALSE) {
  http_response_code(500);
  echo json_encode(['error' => 'Gagal menghentikan kamera/video']);
  exit;
}

echo $result;
?>
