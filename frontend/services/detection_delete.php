<?php
require_once '../config.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$token = $_POST['token'] ?? null;

if (!$id || !$token) {
    http_response_code(400);
    echo json_encode(["error" => "id and token are required"]);
    exit;
}

$ch = curl_init("$API_BASE_URL/detection/$id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($http_code);
echo $response;
