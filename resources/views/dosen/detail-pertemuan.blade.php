<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pertemuan</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-5">

        <h1 class="text-2xl font-bold">
            Data Absensi Pertemuan {{ $id }}
        </h1>

        <a href="/dosen"
           class="bg-gray-700 text-white px-4 py-2 rounded">
            Kembali
        </a>

    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full border-collapse">

            <thead class="bg-gray-100">

                <tr>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">NIM</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Mode</th>
                    <th class="p-3 border">Waktu</th>
                    <th class="p-3 border">Bukti</th>
                </tr>

            </thead>

            <tbody>

            @forelse($absensis as $absen)

                <tr>

                    <td class="p-3 border">
                        {{ $absen->mahasiswa->nama }}
                    </td>

                    <td class="p-3 border">
                        {{ $absen->mahasiswa->nim }}
                    </td>

                    <td class="p-3 border">
                        {{ ucfirst($absen->status) }}
                    </td>

                    <td class="p-3 border">
                        {{ ucfirst($absen->mode_kuliah) }}
                    </td>

                    <td class="p-3 border">
                        {{ $absen->waktu }}
                    </td>

                    <td class="p-3 border">

                        @if($absen->bukti)

                            <a href="{{ asset('storage/' . $absen->bukti) }}"
                               target="_blank"
                               class="text-blue-600 underline">

                               Lihat Bukti

                            </a>

                        @else

                            -

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6"
                        class="text-center p-5 text-gray-500">

                        Belum ada data absensi

                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>