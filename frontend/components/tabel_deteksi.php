<div id="tab-tabel" class="tab-section hidden">
  <!-- Header -->
  <div class="flex items-center gap-x-2 mb-6">
    <img src="https://www.svgrepo.com/show/532227/table-columns.svg" class="w-6 h-6">
    <h2 class="text-2xl font-semibold">Tabel Deteksi</h2>
  </div>

  <div class="bg-white rounded-xl shadow p-6">
    <!-- Filter Dropdown -->
    <div class="mb-4 flex items-center gap-4">
      <label for="roomFilter" class="text-sm font-medium text-gray-700">Filter Ruangan:</label>
      <select id="roomFilter" onchange="loadDetectionTable()" 
        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
        <option value="">Semua</option>
        <!-- opsi ruangan dimuat dari JS -->
      </select>
    </div>

    <!-- Tabel Data Deteksi -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
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
          <!-- Data akan dirender oleh JS -->
        </tbody>
      </table>
    </div>
  </div>
</div>
