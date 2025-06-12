<div id="tab-realtime" class="tab-section hidden">
  <!-- Header -->
  <div class="flex items-center gap-x-2 mb-6">
    <img src="https://www.svgrepo.com/show/479333/face-recognition-1.svg" class="w-6 h-6">
    <h2 class="text-2xl font-semibold">Realtime Detection</h2>
  </div>

  <!-- Kontrol Kamera Tunggal (Dropdown) -->
  <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
    <div class="mb-4">
      <label for="selectCamera" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
      <div class="flex gap-3">
        <select id="selectCamera"
          class="border border-gray-300 rounded-lg px-4 py-2 w-1/2 focus:ring-2 focus:ring-blue-500">
          <option value="">Pilih Kamera</option>
        </select>
        <button id="startBtn"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
          Mulai
        </button>
      </div>
    </div>

    <!-- Feed Kamera Tunggal -->
    <div class="mt-6 border rounded-lg overflow-hidden hidden" id="singleCanvasWrapper">
      <img id="videoFeed" src="" alt="Video Stream"
        class="w-full max-h-[500px] object-contain bg-gray-100" />
    </div>
  </div>

  <!-- Separator -->
  <div class="my-10 pt-8 px-4 w-full">
    <!-- Header & tombol semua kamera -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
      <h3 class="text-xl font-semibold text-gray-800">Semua Kamera Aktif</h3>
      <button onclick="showLiveFeeds()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
        Tampilkan Semua Kamera
      </button>
    </div>

    <!-- Feed Multi Kamera -->
    <div id="feeds" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
      <!-- Feed kamera ditambahkan via JS -->
    </div>
  </div>

  <!-- JS untuk isi dropdown kamera + tombol start -->
  <script>
    const API = "http://localhost:8000";

    fetch(`${API}/camera_list`)
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById("selectCamera");
        data.forEach(cam => {
          const opt = document.createElement("option");
          opt.value = cam.camera_id;
          opt.innerText = cam.room_name || `Camera ${cam.camera_id}`;
          select.appendChild(opt);
        });
      })
      .catch(err => {
        console.error("Gagal memuat daftar kamera:", err);
        alert("Tidak dapat memuat daftar kamera.");
      });

    document.getElementById("startBtn").addEventListener("click", async () => {
      const id = document.getElementById("selectCamera").value;
      if (!id) return alert("Silakan pilih kamera terlebih dahulu.");

      // Cegah duplikat feed
      if (document.getElementById(`cam-wrapper-${id}`)) {
        alert("Kamera ini sudah aktif.");
        return;
      }

      await fetch(`${API}/start_camera?camera_id=${encodeURIComponent(id)}`, { method: "POST" });

      // tampilkan di atas (opsional)
      document.getElementById("videoFeed").src = `${API}/video_feed?camera_id=${encodeURIComponent(id)}`;
      document.getElementById("singleCanvasWrapper").classList.remove("hidden");

      // tambahkan ke feeds bawah
      const cameras = await fetch(`${API}/camera_list`).then(res => res.json());
      const cam = cameras.find(c => c.camera_id == id);
      if (cam) {
        addFeedElement(cam);
        document.getElementById("feeds").classList.remove("hidden");
      }
    });

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
          <button onclick="stopCamera('${cam.camera_id}')"
            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">Stop</button>
          <button onclick="capture('${cam.camera_id}')"
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">Simpan</button>
        </div>
      `;

      container.appendChild(wrapper);
    }

    function stopCamera(id) {
      fetch(`${API}/stop_camera?camera_id=${encodeURIComponent(id)}`, { method: "POST" })
        .then(() => {
          const el = document.getElementById(`cam-wrapper-${id}`);
          if (el) el.remove();
          if (document.getElementById("feeds").children.length === 0) {
            document.getElementById("feeds").classList.add("hidden");
          }
        });
    }

    function capture(id) {
      fetch(`${API}/capture?camera_id=${encodeURIComponent(id)}`, { method: "POST" })
        .then(res => res.json())
        .then(data => {
          alert(`Terdeteksi ${data.person_count} orang`);
        });
    }
  </script>
</div>
