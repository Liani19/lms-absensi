<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Dosen</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body class="bg-gray-100 font-sans text-gray-700">

<!-- HEADER -->
<div class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center shadow">

  <div>
    <h1 class="text-xl font-bold">
      Dashboard Dosen
    </h1>

    <p class="text-sm opacity-80">
      Sistem Presensi Perkuliahan
    </p>
  </div>

  <div class="flex items-center gap-3">
    <div class="bg-blue-600 px-3 py-1 rounded-full text-sm">
      Dosen
    </div>

    <span>
      <strong>Nama Dosen</strong>
    </span>
  </div>

</div>

<div class="flex">

  <!-- SIDEBAR -->
  <aside class="w-64 bg-white min-h-screen shadow-md border-r p-4 space-y-2">

    <a href="/dosen"
       class="flex items-center gap-3 p-2 rounded bg-blue-100 text-blue-700">

      <i class="ph ph-house"></i>
      Dashboard
    </a>

    <a href="#"
       class="flex items-center gap-3 p-2 rounded hover:bg-gray-100">

      <i class="ph ph-calendar"></i>
      Pertemuan
    </a>

    <a href="#"
       class="flex items-center gap-3 p-2 rounded hover:bg-gray-100">

      <i class="ph ph-users"></i>
      Daftar Hadir
    </a>

  </aside>

  <!-- MAIN -->
  <main class="flex-1 p-6">

    <!-- CARD -->
    <div class="grid grid-cols-3 gap-4 mb-6">

      <div class="bg-white p-5 rounded-xl shadow">
        <div class="text-sm text-gray-500">
          Total Pertemuan
        </div>

        <div class="text-3xl font-bold mt-2">
          12
        </div>
      </div>

      <div class="bg-white p-5 rounded-xl shadow">
        <div class="text-sm text-gray-500">
          Presensi Aktif
        </div>

        <div class="text-3xl font-bold mt-2 text-green-600">
          {{ $presensiAktif }}
        </div>
      </div>

      <div class="bg-white p-5 rounded-xl shadow">
        <div class="text-sm text-gray-500">
          Mahasiswa Hadir
        </div>

        <div class="text-3xl font-bold mt-2 text-blue-600">
          {{ \App\Models\Absensi::where('status', 'hadir')->count() }}
        </div>
      </div>

    </div>


  @php
    $pertemuanAktif = collect($pertemuan)
        ->firstWhere('status', 'open');
@endphp

@if($pertemuanAktif)

<div class="bg-green-100 border border-green-300 text-green-800 px-5 py-4 rounded-xl mb-6 shadow-sm">

    <div class="flex items-center gap-3">

        <div class="text-2xl">
            🟢
        </div>

        <div>

            <div class="font-semibold text-lg">
                
            </div>

            <div class="text-sm mt-1">

                {{ $pertemuanAktif['nama'] }}

                •

                Mode:
                {{ ucfirst($pertemuanAktif['mode']) }}

            </div>

        </div>

    </div>

</div>

@else

<div class="bg-red-100 border border-red-300 text-red-800 px-5 py-4 rounded-xl mb-6 shadow-sm">

    <div class="flex items-center gap-3">

        <div class="text-2xl">
            🔴
        </div>

        <div>

            <div class="font-semibold text-lg">
                Tidak Ada Presensi Aktif
            </div>

            <div class="text-sm mt-1">
                Semua presensi sedang ditutup
            </div>

        </div>

    </div>

</div>

@endif


  <!-- CONTROL PANEL -->
<div class="bg-white rounded-xl shadow p-4 mb-6">

    <div class="flex flex-wrap gap-3 items-center">

        <!-- PILIH PERTEMUAN -->
        <select id="pertemuanSelect"
                class="border rounded-lg px-3 py-2">

            @foreach($pertemuan as $item)

                <option value="{{ $item['id'] }}">

                    {{ $item['nama'] }}

                </option>

            @endforeach

        </select>

        <!-- MODE -->
        <select id="modeSelect"
                class="border rounded-lg px-3 py-2">

            <option value="offline">
                Tatap Muka
            </option>

            <option value="online">
                Online
            </option>

        </select>

        <!-- FORM BUKA -->
        <form action="/dosen/buka-presensi"
              method="POST"
              id="formBuka">

            @csrf

            <input type="hidden"
                   name="pertemuan_id"
                   id="inputPertemuan">

            <input type="hidden"
                   name="mode_kuliah"
                   id="inputMode">

            <input type="hidden"
                   name="selected_pertemuan"
                   id="selectedPertemuanInput">

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

                Buka Presensi

            </button>

        </form>

        <!-- FORM TUTUP -->
        <form action="/dosen/tutup-presensi"
              method="POST"
              id="formTutup">

            @csrf

            <input type="hidden"
                   name="pertemuan_id"
                   id="inputPertemuanTutup">

            <input type="hidden"
                  name="selected_pertemuan"
                  id="selectedPertemuanInputTutup">

            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">

                Tutup Presensi

            </button>

        </form>

    </div>

</div>


    <!-- TABEL PRESENSI -->
<div class="bg-white rounded-xl shadow overflow-x-auto">

    <div class="p-4 border-b">
        <h2 class="font-semibold text-lg">
            Presensi Mahasiswa
        </h2>
    </div>

    <table class="min-w-max w-full text-sm border-collapse">
        <thead class="bg-gray-100">

            <tr>

                <th class="p-4 border text-left">
                    Nama Mahasiswa
                </th>

               @foreach($pertemuan as $item)

                <th class="p-4 border text-center min-w-[120px]">

                <div class="font-semibold text-gray-700">
                    Pertemuan {{ $item['id'] }}
                </div>

                <div class="text-[10px] mt-1">

    @if($item['status'] == 'open')

        <span class="text-green-600 font-semibold">
            🟢 OPEN
        </span>

    @else

        <span class="text-red-500 font-semibold">
            🔴 CLOSED
        </span>

    @endif

    •

    <span class="text-gray-500">
        {{ ucfirst($item['mode']) }}
    </span>

</div>

                </th>

              @endforeach

            </tr>

        </thead>

        <tbody>

            @foreach($mahasiswas as $mhs)

            <tr class="hover:bg-gray-50">

                <td class="p-4 border font-medium">

                    <div>
                        {{ $mhs->nama }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $mhs->nim }}
                    </div>

                </td>

                @foreach($pertemuan as $item)

                @php

                    $data = $absensis
                        ->where('mahasiswa_id', $mhs->id)
                        ->where('pertemuan_id', $item['id'])
                        ->first();

                @endphp

                <td class="p-4 border text-center">

                    @if($data)

                    @if($data->status == 'hadir')

                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">
                            Hadir
                        </span>

                        <div class="text-[10px] text-gray-500 mt-1">
                            {{ \Carbon\Carbon::parse($data->waktu)->format('H:i') }}
                        </div>

                    @elseif($data->status == 'izin')

                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">
                             Izin
                        </span>

                        @if($data->bukti)

                        <div class="mt-1">

                            <a href="{{ asset('storage/' . $data->bukti) }}"
                                 target="_blank"
                                class="text-[10px] text-blue-600 underline">

                                 Lihat Bukti

                            </a>

                     </div>
                    
                 @endif

                @elseif($data->status == 'sakit')

                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">
                        Sakit
                    </span>

                    @if($data->bukti)

                    <div class="mt-1">

                        <a href="{{ asset('storage/' . $data->bukti) }}"
                        target="_blank"
                        class="text-[10px] text-blue-600 underline">

                            Lihat Bukti

                        </a>

                    </div>

                @endif

            @endif   

                    @else

                        <span class="text-gray-300">
                            -
                        </span>

                    @endif

                </td>

                @endforeach

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

<script>

const pertemuanSelect = document.getElementById('pertemuanSelect');
const modeSelect = document.getElementById('modeSelect');

function updateForm()
{
    // isi hidden input
    document.getElementById('inputPertemuan').value =
        pertemuanSelect.value;

    document.getElementById('inputMode').value =
        modeSelect.value;

    document.getElementById('inputPertemuanTutup').value =
        pertemuanSelect.value;

    // simpan ke localStorage
    localStorage.setItem(
        'selected_pertemuan',
        pertemuanSelect.value
    );
}

// AMBIL DATA TERAKHIR
window.addEventListener('load', () => {

    const savedPertemuan =
        localStorage.getItem('selected_pertemuan');

    if (savedPertemuan)
    {
        pertemuanSelect.value = savedPertemuan;
    }

    updateForm();
});

// EVENT
pertemuanSelect.addEventListener(
    'change',
    updateForm
);

modeSelect.addEventListener(
    'change',
    updateForm
);

</script>

</body>
</html>