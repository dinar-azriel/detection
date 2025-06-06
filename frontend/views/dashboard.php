<?php
session_start();
if (!isset($_SESSION['token'])) {
  header("Location: login.php");
  exit;
}
require_once '../config.php';

// Ambil daftar kamera
$stmt = $pdo->query("SELECT camera_id, room_name FROM cameras ORDER BY camera_id ASC");
$cameras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Deteksi Mahasiswa</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="dashboard">
    <h1>Dashboard - Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <a href="logout.php" class="btn-logout">Logout</a>
    <a href="cameras.php" class="btn-link">Manajemen Kamera</a>

    <h2>Live Stream</h2>
    <form id="camera-form">
      <label for="camera_id">Pilih Kamera:</label>
      <select id="camera_id" name="camera_id">
        <?php foreach ($cameras as $cam): ?>
          <option value="<?= $cam['camera_id'] ?>"><?= $cam['room_name'] ?> (ID <?= $cam['camera_id'] ?>)</option>
        <?php endforeach; ?>
      </select>
      <button type="button" onclick="startStream()">Mulai Stream</button>
      <button type="button" onclick="stopCamera()">Stop Kamera</button>
      <button type="button" onclick="capture()">Capture</button>
    </form>

    <div id="video-container" style="margin-top: 20px;">
      <img id="video" src="" width="640" height="480">
    </div>

    <h2>Hasil Deteksi</h2>
    <h3>Filter Ruangan:</h3>
    <select id="filter-room" onchange="loadDetections()">
      <option value="">-- Semua Ruangan --</option>
      <?php foreach ($cameras as $cam): ?>
        <option value="<?= htmlspecialchars($cam['room_name']) ?>"><?= $cam['room_name'] ?></option>
      <?php endforeach; ?>
    </select>

    <table border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th>Waktu</th>
          <th>Ruang</th>
          <th>Jumlah Orang</th>
          <th>Gambar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="detection-table">
        <!-- data akan diisi oleh JS -->
      </tbody>
    </table>
  </div>

  <script>
    let currentCamId = null;
    const video = document.getElementById('video');
    const table = document.getElementById('detection-table');

    function startStream() {
      currentCamId = document.getElementById('camera_id').value;
      fetch(`../api/start_camera.php?camera_id=${currentCamId}`);
      video.src = `http://localhost:8000/video_feed?camera_id=${currentCamId}`;
    }

    function stopCamera() {
      if (!currentCamId) return;
      fetch(`../api/stop_camera.php?camera_id=${currentCamId}`);
      video.src = "";
    }

    function capture() {
      if (!currentCamId) return;
      fetch(`../api/capture.php?camera_id=${currentCamId}`)
        .then(res => res.json())
        .then(() => loadDetections());
    }

    function deleteRow(timestamp) {
      if (!confirm("Yakin ingin menghapus data ini?")) return;
      fetch(`../api/delete_detection.php?timestamp=${encodeURIComponent(timestamp)}`)
        .then(() => loadDetections());
    }

    function loadDetections() {
      const selectedRoom = document.getElementById('filter-room').value;
      let url = '../api/fetch_detections.php';
      if (selectedRoom) {
        url += `?room=${encodeURIComponent(selectedRoom)}`;
      }

      fetch(url)
        .then(res => res.json())
        .then(data => {
          table.innerHTML = "";
          data.forEach(item => {
            const row = `<tr>
              <td>${item.timestamp}</td>
              <td>${item.room_name}</td>
              <td>${item.person_count}</td>
              <td><img src="../${item.image_path}" width="100"></td>
              <td><button onclick="deleteRow('${item.timestamp}')">Hapus</button></td>
            </tr>`;
            table.insertAdjacentHTML('beforeend', row);
          });
        });
    }

    window.onload = loadDetections;
  </script>
</body>
</html>
