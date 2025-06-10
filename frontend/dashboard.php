<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Deteksi Mahasiswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Outfit', sans-serif;
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-700">
  <div class="flex h-screen overflow-hidden">
    <?php include 'components/sidebar.php'; ?>
    <main class="flex-1 p-8 overflow-y-auto ml-64 bg-white shadow-inner rounded-tl-3xl">
      <?php include 'components/register_kamera.php'; ?>
      <?php include 'components/realtime_detection.php'; ?>
      <?php include 'components/tabel_deteksi.php'; ?>
    </main>
  </div>
  <script src="assets/dashboard.js"></script>
</body>
</html>
