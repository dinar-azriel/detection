<?php
session_start();
if (isset($_SESSION['token'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="auth-form">
    <h2>Login</h2>
    <form id="loginForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <div class="link">
            <p>Belum punya akun? <a href="register.php">Register</a></p>
        </div>
        <p id="message" style="color: red; text-align: center;"></p>
    </form>
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
