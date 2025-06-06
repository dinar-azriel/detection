<?php
$env = parse_ini_file(__DIR__ . '/.env');

$API_BASE_URL = $env['API_BASE_URL'];
$DB_HOST = $env['DB_HOST'];
$DB_NAME = $env['DB_NAME'];
$DB_USER = $env['DB_USER'];
$DB_PASS = $env['DB_PASS'];

try {
    $pdo = new PDO("pgsql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
