<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Deteksi Mahasiswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-sm">

<div class="flex h-screen">
  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r shadow-md flex flex-col">
    <div class="p-6 text-blue-600 font-bold text-xl">📸 Deteksi Mahasiswa</div>
    <nav class="flex-1 space-y-1 px-4">
      <button onclick="showTab('register')" class="tab-btn w-full text-left px-4 py-2 rounded hover:bg-blue-100 text-gray-700">📷 Register Kamera</button>
      <button onclick="showTab('realtime')" class="tab-btn w-full text-left px-4 py-2 rounded hover:bg-blue-100 text-gray-700">🎥 Realtime Detection</button>
      <button onclick="showTab('tabel')" class="tab-btn w-full text-left px-4 py-2 rounded hover:bg-blue-100 text-gray-700">📊 Tabel Deteksi</button>
    </nav>
    <div class="px-4 pb-4">
      <button onclick="logout()" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">Logout</button>
    </div>
  </aside>

  <!-- Content -->
  <main class="flex-1 p-6 overflow-auto">
    <!-- Register Kamera -->
    <div id="tab-register" class="tab-section">
      <h2 class="text-xl font-semibold mb-4">📷 Register Kamera</h2>
      <form id="registerForm" class="space-y-4 max-w-md">
        <input type="number" name="camera_id" placeholder="Camera ID" required class="w-full border px-4 py-2 rounded" />
        <input type="text" name="room_name" placeholder="Nama Ruangan" required class="w-full border px-4 py-2 rounded" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Kamera</button>
      </form>
      <div class="mt-6">
        <h3 class="font-semibold mb-2">Daftar Kamera</h3>
        <ul id="cameraList" class="space-y-2 text-gray-700"></ul>
      </div>
    </div>

    <!-- Realtime -->
    <div id="tab-realtime" class="tab-section hidden">
      <h2 class="text-xl font-semibold mb-4">🎥 Realtime Detection</h2>
      <div class="mb-4">
        <label class="block mb-1">Pilih Kamera</label>
        <select id="selectCamera" class="border px-4 py-2 rounded"></select>
        <button id="startBtn" class="ml-2 bg-green-600 text-white px-3 py-2 rounded">Mulai</button>
        <button id="stopBtn" class="ml-2 bg-red-500 text-white px-3 py-2 rounded">Stop</button>
        <button id="captureBtn" class="ml-2 bg-yellow-400 px-3 py-2 rounded">📸 Simpan</button>
      </div>
      <div>
        <img id="videoFeed" src="" alt="Video Stream" class="rounded border max-w-full" />
      </div>
    </div>

    <!-- Tabel Deteksi -->
    <div id="tab-tabel" class="tab-section hidden">
      <h2 class="text-xl font-semibold mb-4">📊 Tabel Deteksi</h2>
      <table class="w-full border">
        <thead class="bg-blue-100">
          <tr>
            <th class="border px-2 py-1">#</th>
            <th class="border px-2 py-1">Ruangan</th>
            <th class="border px-2 py-1">Jumlah</th>
            <th class="border px-2 py-1">Waktu</th>
            <th class="border px-2 py-1">Gambar</th>
            <th class="border px-2 py-1">Aksi</th>
          </tr>
        </thead>
        <tbody id="detectionTable"></tbody>
      </table>
    </div>
  </main>
</div>

<script>
const API = "http://localhost:8000";
const token = sessionStorage.getItem("token");

if (!token) window.location.href = "login.php";

function logout() {
  sessionStorage.removeItem("token");
  location.href = "login.php";
}

function showTab(id) {
  document.querySelectorAll(".tab-section").forEach(tab => tab.classList.add("hidden"));
  document.getElementById(`tab-${id}`).classList.remove("hidden");
  document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("bg-blue-100", "text-blue-700", "font-semibold"));
  event.target.classList.add("bg-blue-100", "text-blue-700", "font-semibold");
}

document.getElementById("registerForm").addEventListener("submit", async function(e) {
  e.preventDefault();
  const form = new FormData(this);
  const res = await fetch(`${API}/register_camera`, {
    method: "POST",
    body: JSON.stringify(Object.fromEntries(form)),
    headers: { 'Content-Type': 'application/json' }
  });
  if (res.ok) {
    alert("Kamera berhasil didaftarkan");
    this.reset();
    loadCameraList();
  }
});

async function loadCameraList() {
  const res = await fetch(`${API}/camera_list`);
  const data = await res.json();
  const list = document.getElementById("cameraList");
  const select = document.getElementById("selectCamera");
  list.innerHTML = "";
  select.innerHTML = "";
  data.forEach(cam => {
    list.innerHTML += `
      <li>
        <div class="flex items-center justify-between gap-2">
          <span>Cam ${cam.camera_id} - ${cam.room_name}</span>
          <div class="flex gap-1">
            <button onclick="editCamera(${cam.camera_id}, '${cam.room_name}')" class="text-blue-600 hover:underline">Edit</button>
            <button onclick="deleteCamera(${cam.camera_id})" class="text-red-600 hover:underline">Hapus</button>
          </div>
        </div>
      </li>`;
    select.innerHTML += `<option value="${cam.camera_id}">${cam.room_name}</option>`;
  });
}
setInterval(loadCameraList, 5000);
loadCameraList();

function editCamera(id, oldName) {
  const newName = prompt("Nama ruangan baru:", oldName);
  if (newName && newName !== oldName) {
    fetch(`${API}/camera_update`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ camera_id: id, room_name: newName })
    }).then(res => {
      if (res.ok) loadCameraList();
    });
  }
}
function deleteCamera(id) {
  if (confirm("Hapus kamera ini?")) {
    fetch(`${API}/camera_delete/${id}`, { method: "DELETE" }).then(res => {
      if (res.ok) loadCameraList();
    });
  }
}

document.getElementById("startBtn").onclick = async () => {
  const id = document.getElementById("selectCamera").value;
  await fetch(`${API}/start_camera?camera_id=${id}`, { method: "POST" });
  document.getElementById("videoFeed").src = `${API}/video_feed?camera_id=${id}`;
};
document.getElementById("stopBtn").onclick = async () => {
  const id = document.getElementById("selectCamera").value;
  await fetch(`${API}/stop_camera?camera_id=${id}`, { method: "POST" });
  document.getElementById("videoFeed").src = "";
};
document.getElementById("captureBtn").onclick = async () => {
  const id = document.getElementById("selectCamera").value;
  const res = await fetch(`${API}/capture?camera_id=${id}`, { method: "POST" });
  if (res.ok) {
    alert("Gambar disimpan");
    loadDetections();
  }
};

function formatDate(dateStr) {
  const date = new Date(dateStr);
  const dd = String(date.getDate()).padStart(2, '0');
  const mm = String(date.getMonth() + 1).padStart(2, '0');
  const yy = String(date.getFullYear()).slice(-2);
  const hh = String(date.getHours()).padStart(2, '0');
  const min = String(date.getMinutes()).padStart(2, '0');
  return `${dd}:${mm}:${yy} ${hh}:${min}`;
}

async function loadDetections() {
  const res = await fetch(`${API}/detection_list`);
  const data = await res.json();
  const tbody = document.getElementById("detectionTable");
  tbody.innerHTML = "";
  data.forEach((d, i) => {
    tbody.innerHTML += `
    <tr>
      <td class="border px-2 py-1">${i + 1}</td>
      <td class="border px-2 py-1">${d.room_name}</td>
      <td class="border px-2 py-1">${d.person_count}</td>
      <td class="border px-2 py-1">${formatDate(d.timestamp)}</td>
      <td class="border px-2 py-1">
        <img 
          src="${API}/${d.image_path}" 
          class="max-w-[100px]" 
          onclick="openImage('${API}/${d.image_path}')"
          style="cursor: pointer;"
        >
      </td>
      <td class="border px-2 py-1">
        <button onclick="deleteDetection(${d.id})" class="text-red-600 hover:underline">Hapus</button>
      </td>
    </tr>`;
  });
}
async function deleteDetection(id) {
  if (confirm("Hapus data ini?")) {
    await fetch(`${API}/detection/${id}`, { method: "DELETE" });
    loadDetections();
  }
}

function openImage(imagePath) {
  window.open(imagePath, '_blank');
}

loadDetections();
</script>
</body>
</html>
