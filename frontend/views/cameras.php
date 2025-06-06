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
  <title>Manajemen Kamera</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="dashboard">
    <h1>Manajemen Kamera</h1>
    <a href="dashboard.php">← Kembali ke Dashboard</a>

    <h2>Daftarkan Kamera Baru</h2>
    <?php if (isset($_SESSION['camera_msg'])): ?>
      <div class="message"><?= $_SESSION['camera_msg']; unset($_SESSION['camera_msg']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../api/camera_register.php">
      <label>ID Kamera:</label>
      <input type="number" name="camera_id" required><br>
      <label>Nama Ruang:</label>
      <input type="text" name="room_name" required><br>
      <button type="submit">Simpan</button>
    </form>

    <h2>Daftar Kamera</h2>
    <table border="1" cellpadding="5" cellspacing="0">
      <thead>
        <tr>
          <th>ID Kamera</th>
          <th>Nama Ruang</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cameras as $cam): ?>
          <tr>
            <td><?= $cam['camera_id'] ?></td>
            <td><?= htmlspecialchars($cam['room_name']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
