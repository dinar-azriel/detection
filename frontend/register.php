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
    <title>Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="auth-form">
    <h2>Register</h2>
    <form id="registerForm">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <div class="link">
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
        <p id="message" style="color: green; text-align: center;"></p>
    </form>
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
