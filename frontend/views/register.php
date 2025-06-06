<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - Deteksi Mahasiswa</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="login-container">
    <h2>Registrasi</h2>
    <?php if (isset($_SESSION['error'])): ?>
      <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="../api/register_submit.php">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Daftar</button>
    </form>
    <p>Sudah punya akun? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
