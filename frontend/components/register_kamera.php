<div id="tab-register" class="tab-section">
  <!-- Header dengan ikon + teks sejajar -->
  <div class="flex items-center gap-x-2 mb-6">
    <img src="https://www.svgrepo.com/show/528884/camera.svg" class="w-6 h-6">
    <h2 class="text-2xl font-semibold">Register Kamera</h2>
  </div>

<!-- Form pendaftaran kamera -->
<form id="registerForm" class="space-y-4 max-w-lg bg-white p-6 rounded-xl shadow">
  <!-- Dropdown Kamera -->
  <div>
    <label for="cameraDeviceSelect" class="block mb-1 text-sm font-medium text-gray-700">ID Kamera</label>
    <div class="flex gap-3 items-center">
      <select id="cameraDeviceSelect" name="camera_id"
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        <option value="">Pilih Kamera</option>
      </select>
      <button type="button" onclick="refreshCameraDevices()"
        class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-2 rounded transition">
        🔄
      </button>
    </div>
  </div>

  <!-- Nama Ruangan -->
  <div>
    <label for="room_name" class="block mb-1 text-sm font-medium text-gray-700">Nama Ruangan</label>
    <input type="text" id="room_name" name="room_name" placeholder="Contoh: Lab 101"
      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
  </div>

  <!-- Label Kamera (Opsional) -->
  <div>
    <label for="camera_label" class="block mb-1 text-sm font-medium text-gray-700">Label Kamera (Opsional)</label>
    <input type="text" id="camera_label" name="label" placeholder="Contoh: DroidCam / Webcam Laptop"
      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
  </div>

  <!-- Tombol Simpan -->
  <div class="flex justify-end">
    <button type="submit"
      class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
      Simpan Kamera
    </button>
  </div>
</form>



  <!-- Tabel daftar kamera -->
  <div class="mt-10">
    <h3 class="text-lg font-semibold mb-4">Daftar Kamera</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-xl shadow">
        <thead class="bg-blue-50 text-left text-sm font-semibold text-gray-700">
          <tr>
            <th class="px-4 py-3">ID</th>
            <th class="px-4 py-3">Nama Ruangan</th>
            <th class="px-4 py-3">Label Kamera</th>
            <th class="px-4 py-3">Aksi</th>
          </tr>
        </thead>
        <tbody id="cameraList" class="text-sm text-gray-800 divide-y divide-gray-200">
          <!-- data kamera akan di-render di sini -->
        </tbody>
      </table>
    </div>
  </div>
</div>
