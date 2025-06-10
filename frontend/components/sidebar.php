<aside class="fixed top-0 left-0 z-40 w-64 h-full bg-white border-r shadow-lg">
  <div class="flex items-center gap-3 px-6 py-5 border-b">
    <img src="https://www.svgrepo.com/show/520490/student.svg" alt="Logo" class="w-12 h-12 text-blue-600">
    <span class="text-xl font-semibold text-blue-600">Deteksi Mahasiswa</span>
  </div>
  <nav class="flex flex-col px-4 py-6 space-y-2">
    <button onclick="showTab('register', this)" class="tab-btn flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition">
      <img src="https://www.svgrepo.com/show/528884/camera.svg" class="w-5 h-5">
      <span>Register Kamera</span>
    </button>
    <button onclick="showTab('realtime', this)" class="tab-btn flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition">
      <img src="https://www.svgrepo.com/show/479333/face-recognition-1.svg" class="w-5 h-5">
      <span>Realtime Detection</span>
    </button>
    <button onclick="showTab('tabel', this)" class="tab-btn flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-blue-50 transition">
      <img src="https://www.svgrepo.com/show/532227/table-columns.svg" class="w-5 h-5">
      <span>Tabel Deteksi</span>
    </button>
  </nav>
  <div class="absolute bottom-0 w-full px-4 py-4 border-t">
    <button onclick="logout()" class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">Logout</button>
  </div>
</aside>
