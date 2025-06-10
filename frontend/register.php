<?php
session_start();
if (isset($_SESSION['token'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Buat Akun Baru</h2>
    <p class="text-sm text-center text-gray-500 mb-6">Isi detail berikut untuk memulai.</p>

    <form id="registerForm" class="space-y-5">
      <div>
        <label for="register-username" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
        <input type="text" id="register-username" name="username" placeholder="Pilih username"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-400 focus:border-yellow-400 block w-full p-2.5" required />
      </div>

      <div>
        <label for="register-password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="register-password" name="password" placeholder="Buat password"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-400 focus:border-yellow-400 block w-full p-2.5" required />
      </div>

      <div>
        <label for="register-password-confirm" class="block mb-1 text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password" id="register-password-confirm" name="password-confirm" placeholder="Ulangi password"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-400 focus:border-yellow-400 block w-full p-2.5" required />
      </div>

      <button type="submit"
        class="w-full text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
        Register
      </button>

      <p id="message" class="text-sm text-center font-medium mt-2 text-red-500"></p>

      <div class="text-sm text-center text-gray-600">
        Sudah punya akun? <a href="login.php" class="text-blue-600 hover:underline">Login</a>
      </div>
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
      const messageEl = document.getElementById("message");

      if (res.ok) {
        messageEl.innerText = "Registrasi berhasil. Silakan login.";
        messageEl.classList.remove("text-red-500");
        messageEl.classList.add("text-green-500");
      } else {
        messageEl.innerText = data.detail || data.error;
        messageEl.classList.remove("text-green-500");
        messageEl.classList.add("text-red-500");
      }
    });
  </script>
</body>
</html>
