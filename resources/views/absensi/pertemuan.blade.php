<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pertemuan</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100 font-sans text-gray-700">

<!-- HEADER -->
<div class="bg-green-700 text-white px-6 py-4 flex justify-between items-center shadow">
  <div class="flex items-center gap-4">
    <div class="bg-white text-green-700 font-bold px-3 py-1 rounded">
      LMS
    </div>
    <div>
      <h1 class="text-lg font-semibold">MKB701 | Metodologi Penelitian</h1>
      <p class="text-xs opacity-80">Kelas C.23 • D3 Manajemen Informatika</p>
    </div>
  </div>

  <div class="text-sm flex items-center gap-3">
    <div class="bg-green-600 px-3 py-1 rounded-full">
      Mahasiswa
    </div>
    <span><strong>Liani Siti</strong></span>
  </div>
</div>

<div class="flex">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-white shadow-md border-r min-h-screen p-4 space-y-2">
    <a href="/pertemuan" class="flex items-center gap-3 text-sm p-2 rounded bg-green-100 text-green-700">
      <i class="ph ph-books"></i> Pertemuan
    </a>

    <a href="#" class="flex items-center gap-3 text-sm p-2 rounded hover:bg-gray-100">
      <i class="ph ph-clipboard-text"></i> Presensi
    </a>
  </aside>

  <!-- MAIN -->
  <main class="flex-1 p-6 max-w-5xl mx-auto">

    <!-- BOX PRESENSI -->
    <div class="bg-white p-5 rounded-xl shadow mb-5 border">

      <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
        <i class="ph ph-calendar-check"></i>
        Presensi Hari Ini
      </h3>

    @php
         $aktif = collect($pertemuan)->firstWhere('open', true);
    @endphp

    @if(!$aktif)

        <div class="bg-yellow-100 text-yellow-700 p-3 rounded">
          ⏳ Presensi belum dibuka
        </div>

    @elseif($aktif['sudah_absen'])

        <div class="bg-green-100 text-green-700 p-3 rounded">
        ✅ Anda sudah melakukan presensi pada {{ $aktif['nama'] }}
        </div>

    @else

        <div class="flex justify-between items-center">
        <span>
          Silakan lakukan presensi untuk {{ $aktif['nama'] }}
        </span>

        <button onclick="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
          Presensi Sekarang
        </button>
        </div>

    @endif

    </div>

    <!-- ================= DAFTAR PERTEMUAN ================= -->
<div class="space-y-3 mt-6">

  @for($i = 1; $i <= 12; $i++)

    <div class="bg-white p-4 rounded-xl shadow border flex justify-between items-center hover:shadow-md transition">

      <div>
        <h4 class="font-semibold text-gray-800">
          Pertemuan {{ $i }}
        </h4>

        <p class="text-sm text-gray-500">
          Selasa • 12:30 - 14:10
        </p>
      </div>

      @if($i == $pertemuanAktif && $absensiDibuka)

        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
          Open
        </span>

      @else

        <span class="bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full">
          Closed
        </span>

      @endif

    </div>

  @endfor

</div>

  </main>

</div>

<!-- ================= MODAL ABSENSI ================= -->
<div id="modalAbsensi" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">

  <div id="modalContent" class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg transform scale-90 opacity-0 transition duration-300">

  <div class="mb-3">
  <span class="text-sm text-gray-500">Mode Kuliah:</span>

  @if($modeKuliah == 'online')
    <div class="text-blue-600 font-semibold">
      Online
    </div>
  @else
    <div class="text-green-600 font-semibold">
      Tatap Muka
    </div>
  @endif
</div>

    <h2 class="text-lg font-semibold mb-4">Form Presensi</h2>

     <form id="formAbsensi" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="pertemuan_id" value="{{ $pertemuanAktif }}">
    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswaId }}">

    <div class="mb-3">
    <label class="block mb-1">
    Pilih Mahasiswa
    </label>

    
  </div>

      <input type="hidden"
       name="mode_kelas"
       id="mode_kelas"
       value="{{ $modeKuliah }}">


      <label class="block mb-1">Status</label>
      <select name="status" id="status" class="w-full border p-2 rounded mb-3" onchange="aturForm()">
        <option value="hadir">Hadir</option>
        <option value="izin">Izin</option>
        <option value="sakit">Sakit</option>
      </select>

      <!-- LOKASI -->
      <div id="lokasiBox">
        <button type="button" onclick="ambilLokasi()" class="bg-green-700 text-white px-3 py-1 rounded">
          Ambil Lokasi
        </button>
        <p id="infoLokasi" class="text-sm mt-1">Belum diambil</p>
        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lon">
      </div>

      <!-- UPLOAD -->
      <div id="uploadBox" style="display:none" class="mt-3">
        <input type="file" name="bukti" class="w-full border p-2 rounded">
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="closeModal()" class="px-3 py-1 border rounded">
          Batal
        </button>

        <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded">
          Kirim
        </button>
      </div>

    </form>

  </div>
</div>

<!-- ================= MODAL SUCCESS ================= -->
<div id="modalSuccess" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg text-center shadow-lg">
    <div class="text-green-600 text-4xl mb-2">✔️</div>
    <p class="font-semibold">Absensi Berhasil</p>
  </div>
</div>

<!-- ================= LOADING ================= -->
<div id="loading" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
  <div class="bg-white p-6 rounded-lg flex items-center gap-3 shadow">
    <div class="w-5 h-5 border-4 border-green-500 border-t-transparent rounded-full animate-spin"></div>
    <span>Memproses...</span>
  </div>
</div>

<!-- ================= SCRIPT ================= -->
<script>
function openModal() {
  const modal = document.getElementById('modalAbsensi');
  const content = document.getElementById('modalContent');

  modal.classList.remove('hidden');
  modal.classList.add('flex');

  setTimeout(() => {
    content.classList.remove('scale-90','opacity-0');
    content.classList.add('scale-100','opacity-100');
  }, 10);
  aturForm();
}



function closeModal() {
  const modal = document.getElementById('modalAbsensi');
  const content = document.getElementById('modalContent');

  content.classList.add('scale-90','opacity-0');

  setTimeout(() => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }, 200);
}

function aturForm() {

  const tipe = document.getElementById("mode_kelas").value;
  const status = document.getElementById("status").value;

  const lokasi = document.getElementById("lokasiBox");
  const upload = document.getElementById("uploadBox");

  // OFFLINE + HADIR = wajib GPS
  if (tipe === "offline" && status === "hadir") {
      lokasi.style.display = "block";
  } else {
      lokasi.style.display = "none";
  }

  // ONLINE / IZIN / SAKIT = upload bukti
  if (
      tipe === "online" ||
      status === "izin" ||
      status === "sakit"
  ) {
      upload.style.display = "block";
  } else {
      upload.style.display = "none";
  }
}

function ambilLokasi() {

  navigator.geolocation.getCurrentPosition(

    (pos) => {

      console.log("LAT:", pos.coords.latitude);
      console.log("LON:", pos.coords.longitude);

      document.getElementById("lat").value =
        pos.coords.latitude;

      document.getElementById("lon").value =
        pos.coords.longitude;

      document.getElementById("infoLokasi").innerText =
        "✅ Lokasi diambil";

    },

    (err) => {

      console.log(err);

      alert("Lokasi gagal diambil");

    },

    {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 0
    }

  );
}

document.getElementById("formAbsensi").addEventListener("submit", function(e){
  e.preventDefault();

  const tipe = document.getElementById("mode_kelas").value;
  const status = document.getElementById("status").value;
  const lat = document.getElementById("lat").value;

  if (tipe === "offline" && status === "hadir" && !lat) {
    alert("Ambil lokasi dulu!");
    return;
  }

  document.getElementById("loading").classList.remove("hidden");

  let formData = new FormData(this);
  for (let pair of formData.entries()) {
    console.log(pair[0], pair[1]);
}

  fetch("/absensi", {
    method: "POST",
    body: formData
  })
  .then(async response => {

  const data = await response.json();

  if (!response.ok) {

    alert(data.message);

    document.getElementById("loading")
      .classList.add("hidden");

    return;
  }

  document.getElementById("loading")
    .classList.add("hidden");

  closeModal();

  document.getElementById("modalSuccess")
    .classList.remove("hidden");

  setTimeout(() => {
    location.reload();
  }, 1500);

})

  .catch(() => {
    document.getElementById("loading").classList.add("hidden");
    alert("Gagal kirim data");
  });
});

// klik luar modal = close
window.onclick = function(e) {
  const modal = document.getElementById('modalAbsensi');
  if (e.target === modal) {
    closeModal();
  }
}
</script>

</body>
</html>