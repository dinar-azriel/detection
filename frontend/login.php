<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Outfit', sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

  <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Selamat Datang</h2>
    <p class="text-sm text-center text-gray-500 mb-6">Masuk untuk melanjutkan ke dashboard</p>

    <form id="loginForm" class="space-y-5">
      <div>
        <label for="login-username" class="block mb-1 text-sm font-medium text-gray-700">Username</label>
        <input type="text" id="login-username" name="username" placeholder="Masukkan username"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 
                 block w-full p-2.5" required />
      </div>

      <div>
        <label for="login-password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
        <div class="relative">
          <input type="password" id="login-password" name="password" placeholder="••••••••"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 
                   block w-full p-2.5 pr-10" required />
          <button type="button" id="togglePassword"
            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-600 hover:text-gray-800"
            tabindex="-1">
            👁
          </button>
        </div>
      </div>

      <button type="submit"
        class="w-full text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
               hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 
               font-medium rounded-lg text-sm px-5 py-2.5 text-center transition">
        Masuk
      </button>

      <p id="message" class="text-sm text-red-500 text-center font-medium"></p>

      <div class="text-sm text-center text-gray-600">
        Belum punya akun? <a href="register.php" class="text-blue-600 hover:underline">Register</a>
      </div>
    </form>
  </div>

  <script>
    // Redirect jika sudah login
    if (sessionStorage.getItem("token")) {
      window.location.href = "dashboard.php";
    }

    // Submit Login
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
        document.getElementById("message").innerText = data.detail || data.error || "Login gagal";
      }
    });

    // Toggle show password
    document.getElementById("togglePassword").addEventListener("click", function () {
      const passwordField = document.getElementById("login-password");
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      this.textContent = type === "password" ? "👁" : "🙈";
    });
  </script>
</body>
</html>
