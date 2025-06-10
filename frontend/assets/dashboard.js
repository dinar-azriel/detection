const API = "http://localhost:8000";
const token = sessionStorage.getItem("token");
if (!token) window.location.href = "login.php";

function logout() {
  sessionStorage.removeItem("token");
  location.href = "login.php";
}

function showTab(id, element) {
  document.querySelectorAll(".tab-section").forEach(tab => tab.classList.add("hidden"));
  document.getElementById(`tab-${id}`).classList.remove("hidden");
  document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("bg-blue-100", "text-blue-700", "font-semibold"));
  if (element) element.classList.add("bg-blue-100", "text-blue-700", "font-semibold");
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
  const filter = document.getElementById("roomFilter");
  list.innerHTML = "";
  if (select) select.innerHTML = "";
  if (filter) filter.innerHTML = `<option value="">Semua</option>`;

  data.forEach(cam => {
    list.innerHTML += `
      <tr>
        <td class="px-4 py-2">Cam ${cam.camera_id}</td>
        <td class="px-4 py-2">${cam.room_name}</td>
        <td class="px-4 py-2 flex gap-2">
          <button onclick="editCamera(${cam.camera_id}, '${cam.room_name}')"
            class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded-lg transition">
            Edit
          </button>
          <button onclick="deleteCamera(${cam.camera_id})"
            class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded-lg transition">
            Hapus
          </button>
        </td>
      </tr>`;
    if (select) select.innerHTML += `<option value="${cam.camera_id}">${cam.room_name}</option>`;
    if (filter) filter.innerHTML += `<option value="${cam.room_name}">${cam.room_name}</option>`;
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
        <img src="${API}/${d.image_path}" class="max-w-[100px] cursor-pointer" onclick="openImage('${API}/${d.image_path}')">
      </td>
      <td class="px-4 py-2">
      <button onclick="deleteDetection(${d.id})"
      class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition">Hapus
      </button>
      <td>

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