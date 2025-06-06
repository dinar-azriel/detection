<?php
session_start();
require_once '../config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$payload = json_encode([
  "username" => $username,
  "password" => $password
]);

$ch = curl_init("{$API_BASE_URL}/register");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
  $_SESSION['username'] = $username;
  $_SESSION['success'] = "Registrasi berhasil. Silakan login.";
  header("Location: ../views/login.php");
} else {
  $_SESSION['error'] = "Registrasi gagal. Username mungkin sudah terdaftar.";
  header("Location: ../views/register.php");
}
