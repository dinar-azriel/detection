<?php
session_start();
require_once '../config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$payload = json_encode([
  "username" => $username,
  "password" => $password
]);

$ch = curl_init("{$API_BASE_URL}/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
  $data = json_decode($response, true);
  $_SESSION['token'] = $data['token'];
  $_SESSION['username'] = $username;
  header("Location: ../views/dashboard.php");
} else {
  $_SESSION['error'] = "Login gagal. Periksa kembali username dan password.";
  header("Location: ../views/login.php");
}
