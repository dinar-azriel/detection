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

  if (id === "realtime") {
    document.getElementById("feeds").classList.remove("hidden");
  }

  if (id === "tabel") {
    loadDetectionTable();
  }

  if (id === "register") {
    refreshCameraDevices();
  }
}

// === REGISTER CAMERA ===
document.getElementById("registerForm")?.addEventListener("submit", async function(e) {
  e.preventDefault();
  const form = new FormData(this);

  if (!form.get("camera_id")) {
    alert("Pilih ID kamera terlebih dahulu.");
    return;
  }

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

// === REFRESH DROPDOWN DEVICE KAMERA DARI BACKEND ===
async function refreshCameraDevices() {
  const select = document.getElementById("cameraDeviceSelect");
  select.innerHTML = `<option value="">Memuat kamera...</option>`;

  try {
    const res = await fetch(`${API}/available_cameras`);
    const cameras = await res.json();

    if (cameras.length === 0) {
      select.innerHTML = `<option value="">Tidak ada kamera terdeteksi</option>`;
      return;
    }

    select.innerHTML = `<option value="">Pilih Kamera</option>`;
    cameras.forEach(cam => {
      select.innerHTML += `<option value="${cam.camera_id}">${cam.label || `Camera ${cam.camera_id}`}</option>`;
    });
  } catch (err) {
    console.error("Gagal memuat kamera dari backend:", err);
    select.innerHTML = `<option value="">Gagal memuat kamera</option>`;
  }
}

// === LOAD CAMERA LIST ===
async function loadCameraList() {
  const res = await fetch(`${API}/camera_list`);
  const data = await res.json();
  const list = document.getElementById("cameraList");
  const select = document.getElementById("selectCamera");
  const filter = document.getElementById("roomFilter");

  const selectedValue = select?.value || "";

  if (list) list.innerHTML = "";
  if (select) select.innerHTML = "";
  if (filter) filter.innerHTML = `<option value="">Semua</option>`;

  data.forEach(cam => {
    if (list) {
      list.innerHTML += `
  <tr>
    <td class="px-4 py-2">Cam ${cam.camera_id}</td>
    <td class="px-4 py-2">${cam.room_name}</td>
    <td class="px-4 py-2">${cam.label || "-"}</td>
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

    }
    if (select) select.innerHTML += `<option value="${cam.camera_id}">${cam.room_name}</option>`;
    if (filter) filter.innerHTML += `<option value="${cam.room_name}">${cam.room_name}</option>`;
  });

  if (select) select.value = selectedValue;
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

// === START DARI DROPDOWN ===
document.getElementById("startBtn").onclick = async function () {
  const select = document.getElementById("selectCamera");
  const cameraId = parseInt(select.value);
  if (isNaN(cameraId)) return;

  if (document.getElementById(`cam-wrapper-${cameraId}`)) {
    alert("Kamera ini sudah aktif.");
    return;
  }

  await startCamera(cameraId);
  const cameras = await fetch(`${API}/camera_list`).then(res => res.json());
  const cam = cameras.find(c => c.camera_id === cameraId);
  if (cam) addFeedElement(cam);

  document.getElementById("feeds").classList.remove("hidden");
};

// === START SEMUA KAMERA SEKALIGUS ===
async function showLiveFeeds() {
  const res = await fetch(`${API}/camera_list`);
  const cameras = await res.json();

  const container = document.getElementById("feeds");
  container.innerHTML = "";

  if (cameras.length === 0) {
    container.classList.add("hidden");
    return;
  }

  for (const cam of cameras) {
    await startCamera(cam.camera_id);
    addFeedElement(cam);
  }

  container.classList.remove("hidden");
}

function addFeedElement(cam) {
  const container = document.getElementById("feeds");

  const wrapper = document.createElement("div");
  wrapper.className = "bg-white border p-4 rounded-xl shadow";
  wrapper.id = `cam-wrapper-${cam.camera_id}`;

  wrapper.innerHTML = `
    <h3 class="font-semibold text-lg mb-2">Cam ${cam.camera_id} - ${cam.room_name}</h3>
    <img id="cam-feed-${cam.camera_id}" src="${API}/video_feed?camera_id=${cam.camera_id}"
      class="w-full max-h-[400px] bg-gray-100 object-contain mb-2 rounded" />
    <div class="flex justify-center gap-2 mt-2">
      <button onclick="stopCamera(${cam.camera_id})"
        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Stop</button>
      <button onclick="capture(${cam.camera_id})"
        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Simpan</button>
    </div>
  `;

  container.appendChild(wrapper);
}

function startCamera(id) {
  return fetch(`${API}/start_camera?camera_id=${id}`, { method: "POST" });
}

function stopCamera(id) {
  fetch(`${API}/stop_camera?camera_id=${id}`, { method: "POST" })
    .then(() => {
      const el = document.getElementById(`cam-wrapper-${id}`);
      if (el) el.remove();

      if (document.getElementById("feeds").children.length === 0) {
        document.getElementById("feeds").classList.add("hidden");
      }
    });
}

function capture(id) {
  fetch(`${API}/capture?camera_id=${id}`, { method: "POST" })
    .then(res => res.json())
    .then(data => {
      alert(`Jumlah orang terdeteksi: ${data.person_count}`);
      if (typeof loadDetectionTable === "function") {
        loadDetectionTable();
      }
    });
}

// === TABEL DETEKSI ===
async function loadDetectionTable() {
  const res = await fetch(`${API}/detection_list`);
  const data = await res.json();
  const tbody = document.getElementById("detectionTable");
  const filter = document.getElementById("roomFilter");
  const selectedRoom = filter?.value;

  tbody.innerHTML = "";

  let counter = 1;
  data.forEach(row => {
    if (selectedRoom && row.room_name !== selectedRoom) return;

function formatwaktu(timestamp) {
  const date = new Date(timestamp);
  return date
    .toLocaleString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: false
    })
    .replace(' pukul ', ' ') 
    .replace(':', '.');      
}


    tbody.innerHTML += `
      <tr>
        <td class="px-4 py-2">${counter++}</td>
        <td class="px-4 py-2">${row.room_name}</td>
        <td class="px-4 py-2">${row.person_count}</td>
        <td class="px-4 py-2">${formatwaktu(row.timestamp)}</td>
        <td class="px-4 py-2">
  <a href="${API}/${row.image_path}" target="_blank">
    <img src="${API}/${row.image_path}" alt="Deteksi" class="w-20 h-14 object-cover mx-auto rounded hover:opacity-80 transition" />
  </a>
</td>

        <td class="px-4 py-2">
          <button onclick="deleteDetection(${row.id})"
            class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">
            Hapus
          </button>
        </td>
      </tr>
    `;
  });
}

function deleteDetection(id) {
  if (confirm("Yakin ingin menghapus deteksi ini?")) {
    fetch(`${API}/detection/${id}`, { method: "DELETE" })
      .then(res => {
        if (res.ok) loadDetectionTable();
      });
  }
}
