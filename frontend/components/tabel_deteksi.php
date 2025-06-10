<div id="tab-realtime" class="tab-section hidden">
  <!-- Realtime Detection Section -->
  <div class="flex items-center gap-x-2 mb-6">
    <img src="https://www.svgrepo.com/show/530435/video-camera.svg" class="w-6 h-6">
    <h2 class="text-2xl font-semibold">Realtime Detection</h2>
  </div>
  <div class="bg-white rounded-xl shadow p-6 max-w-3xl">
    <!-- Camera Selection and Buttons -->
    <div class="mb-4">
      <label for="selectCamera" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kamera</label>
      <div class="flex gap-3">
        <select id="selectCamera" class="border border-gray-300 rounded-lg px-4 py-2 w-1/2 focus:ring-2 focus:ring-blue-500">
          <!-- options loaded dynamically -->
        </select>
        <button id="startBtn"
          class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
          Mulai
        </button>
        <button id="stopBtn"
          class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
          Stop
        </button>
        <button id="captureBtn"
          class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
          Simpan
        </button>
      </div>
    </div>

    <!-- Video Feed Preview -->
    <div class="mt-6 border rounded-lg overflow-hidden">
      <img id="videoFeed" src="" alt="Video Stream"
        class="w-full max-h-[500px] object-contain bg-gray-100" />
    </div>
  </div>
</div>

<div id="tab-tabel" class="tab-section hidden">
  <!-- Tabel Deteksi Section with SVG Icon -->
  <div class="flex items-center gap-x-2 mb-6">
    <img src="https://www.svgrepo.com/show/532227/table-columns.svg" class="w-6 h-6">
    <h2 class="text-2xl font-semibold">Tabel Deteksi</h2>
  </div>

  <div class="bg-white rounded-xl shadow p-6">
    <!-- Filter Dropdown -->
    <div class="mb-4 flex items-center gap-4">
      <label for="roomFilter" class="text-sm font-medium text-gray-700">Filter Ruangan:</label>
      <select id="roomFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        <option value="">Semua</option>
      </select>
    </div>

    <!-- Data Table -->
    <div class="overflow-x-auto rounded-lg">
      <table class="min-w-full bg-white text-sm text-center text-gray-700">
        <thead class="bg-blue-50 text-sm font-semibold">
          <tr>
            <th class="px-4 py-3 text-center">#</th>
            <th class="px-4 py-3">Ruangan</th>
            <th class="px-4 py-3">Jumlah</th>
            <th class="px-4 py-3">Waktu</th>
            <th class="px-4 py-3">Gambar</th>
            <th class="px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody id="detectionTable" class="divide-y divide-gray-200">
          <!-- Data akan di-render di sini -->
        </tbody>
      </table>
    </div>
  </div>
</div>
