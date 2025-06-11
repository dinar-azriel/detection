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
          <!-- options loaded dynamically -->
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
</div>
