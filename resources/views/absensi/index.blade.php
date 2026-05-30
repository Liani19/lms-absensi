<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Presensi</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100 font-sans text-gray-700">

<!-- HEADER -->
<div class="bg-green-700 text-white px-6 py-4 flex justify-between items-center">
  <div>
    <h1 class="text-xl font-bold">Sistem Absensi</h1>
    <p class="text-sm">Kelas Mahasiswa</p>
  </div>
  <div class="text-sm">
    Hello, <strong>User</strong>
  </div>
</div>

<div class="flex">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-white shadow-md border-r min-h-screen p-4 space-y-4">
    <a href="/pertemuan" class="flex items-center gap-3 text-sm hover:text-green-700">
      <i class="ph ph-books"></i> Pertemuan
    </a>
    <a href="/absensi" class="flex items-center gap-3 text-sm text-green-700">
      <i class="ph ph-clipboard-text"></i> Presensi
    </a>
  </aside>

  <!-- FORM -->
  <main class="flex-1 p-8">
    <div class="bg-white p-6 rounded shadow-md max-w-2xl mx-auto">

      <h2 class="text-xl font-semibold text-green-700 mb-4">
        Presensi Hari Ini
      </h2>

      {{-- NOTIF --}}
      @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">
          {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="bg-red-100 text-red-800 p-2 rounded mb-3">
          {{ session('error') }}
        </div>
      @endif

      <form method="POST" action="/absensi" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="mode_kelas" id="mode_kelas">

        <!-- TIPE -->
        <label class="block mb-1 font-medium">Tipe Kuliah</label>
        <select id="tipe" class="w-full border p-2 rounded mb-3" onchange="aturForm()">
          <option value="offline">Tatap Muka</option>
          <option value="online">Online</option>
        </select>

        <!-- STATUS -->
        <label class="block mb-1 font-medium">Status</label>
        <select name="status" id="status" class="w-full border p-2 rounded mb-3" onchange="aturForm()">
          <option value="hadir">Hadir</option>
          <option value="izin">Izin</option>
          <option value="sakit">Sakit</option>
        </select>

        <!-- GPS -->
        <div id="lokasiBox">
          <button type="button" onclick="ambilLokasi()" class="bg-green-800 text-white px-4 py-2 rounded">
            Ambil Lokasi
          </button>
          <p id="infoLokasi" class="text-sm mt-2">Lokasi belum diambil</p>

          <input type="hidden" name="latitude" id="lat">
          <input type="hidden" name="longitude" id="lon">
        </div>

        <!-- UPLOAD -->
        <div id="uploadBox" class="hidden mt-3">
          <label>Upload Bukti</label>
          <input type="file" name="bukti" class="w-full border p-2 rounded">
        </div>

        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded mt-4">
          Kirim Presensi
        </button>

      </form>

    </div>
  </main>
</div>

<script>
function aturForm() {
    const tipe = document.getElementById("tipe").value;
    const status = document.getElementById("status").value;

    document.getElementById("mode_kelas").value = tipe;

    const lokasi = document.getElementById("lokasiBox");
    const upload = document.getElementById("uploadBox");

    if (tipe === "offline" && status === "hadir") {
        lokasi.style.display = "block";
    } else {
        lokasi.style.display = "none";
    }

    if (status === "izin" || status === "sakit" || tipe === "online") {
        upload.classList.remove("hidden");
    } else {
        upload.classList.add("hidden");
    }
}

function ambilLokasi(){
    navigator.geolocation.getCurrentPosition(pos => {
        document.getElementById("lat").value = pos.coords.latitude;
        document.getElementById("lon").value = pos.coords.longitude;
        document.getElementById("infoLokasi").innerText = "✅ Lokasi diambil";
    });
}

document.addEventListener("DOMContentLoaded", aturForm);
</script>

</body>
</html>