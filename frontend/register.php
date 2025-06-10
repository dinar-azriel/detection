<?php
session_start();
if (isset($_SESSION['token'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="./assets/auth.css">
</head>
<body>
    <div class="container" role="main" aria-label="Register page">
        <h1 id="form-title">Buat Akun Baru</h1>
        <p class="subtitle" id="form-subtitle">Isi detail berikut untuk memulai.</p>

        <div class="auth-form">
            <form id="registerForm">
                <div>
                    <label for="register-username">Username</label>
                    <input type="text" id="register-username" name="username" placeholder="Choose a username" required>
                </div>
                <div>
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" placeholder="Create a password" required>
                </div>
                <div>
                    <label for="register-password-confirm">Confirm Password</label>
                    <input type="password" id="register-password-confirm" name="password-confirm" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="btn btn-yellow">Register</button>
                <div class="link">
                    <p>Sudah punya akun? <a href="login.php">Login</a></p>
                </div>
                <p id="message" class="success-message"></p>
            </form>
        </div>
    </div>

    <script>
    document.getElementById("registerForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const form = new FormData(this);
        const res = await fetch("services/auth_register.php", {
            method: "POST",
            body: form
        });
        const data = await res.json();
        if (res.ok) {
            document.getElementById("message").innerText = "Registrasi berhasil. Silakan login.";
        } else {
            document.getElementById("message").innerText = data.detail || data.error;
        }
    });
    </script>
</body>
</html>
