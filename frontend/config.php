<?php
session_start();
$env = parse_ini_file(__DIR__ . '/.env');
$API_BASE_URL = $env['API_BASE_URL'];
?>
