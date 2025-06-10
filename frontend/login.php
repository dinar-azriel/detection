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
    <title>Login</title>
    <!-- <link rel="stylesheet" href="./assets/auth.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>
<body>
    <div class="container" role="main" aria-label="Login page">
        <h1 id="form-title">Selamat Datang</h1>
        <p class="subtitle" id="form-subtitle">Masuk untuk melanjutkan ke dashboard.</p>

        <div class="auth-form">
            <form id="loginForm">
                <div>
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" placeholder="Your username" required>
                </div>
                <div>
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Masuk</button>
                <div class="link">
                    <p>Belum punya akun? <a href="register.php">Register</a></p>
                </div>
                <p id="message" class="error-message"></p>
            </form>
        </div>
    </div>

    <script>
    document.getElementById("loginForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        const form = new FormData(this);
        const res = await fetch("services/auth_login.php", {
            method: "POST",
            body: form
        });
        const data = await res.json();
        if (res.ok) {
            sessionStorage.setItem("token", data.token);
            window.location.href = "dashboard.php";
        } else {
            document.getElementById("message").innerText = data.detail || data.error;
        }
    });
    </script>
</body>
</html>
