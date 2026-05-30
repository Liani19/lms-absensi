<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Models\PengaturanPresensi;

class AbsensiController extends Controller
{
    // ===============================
    // HALAMAN PERTEMUAN MAHASISWA
    // ===============================
    public function pertemuan(Request $request)
{
    $mahasiswaId = $request->mahasiswa_id ?? 1;
    $hariIni = now()->format('Y-m-d');

    $pertemuan = [];

    for ($i = 1; $i <= 12; $i++) {

    $setting = PengaturanPresensi::where('pertemuan_id', $i)
        ->latest()
        ->first();

    $isOpen = $setting && $setting->is_open;

    $mode = $setting ? $setting->mode_kuliah : 'offline';

    $sudahAbsen = Absensi::where('pertemuan_id', $i)
    ->where('mahasiswa_id', $mahasiswaId)
    ->count() > 0;

    $pertemuan[] = [
        'id' => $i,
        'nama' => "Pertemuan $i",
        'open' => $isOpen,
        'mode' => $mode,
        'sudah_absen' => $sudahAbsen,
     ];
    }

    $pertemuanAktif = null;
    $sudahAbsen = false;

    foreach ($pertemuan as $item) {

    if ($item['open']) {

        $pertemuanAktif = $item['id'];

        $sudahAbsen = $item['sudah_absen'];

        break;
         }
            }

    $adaJadwal = true;
    $absensiDibuka = $pertemuanAktif ? true : false;

    $modeKuliah = $pertemuanAktif
        ? $pertemuan[$pertemuanAktif - 1]['mode']
        : 'offline';


    return view('absensi.pertemuan', compact(
    'pertemuan',
    'pertemuanAktif',
    'adaJadwal',
    'absensiDibuka',
    'mahasiswaId',
    'modeKuliah'
    ));
    }

    // ===============================
    // HALAMAN FORM ABSENSI
    // ===============================
    public function index()
    {
        $sekarang = now();

        $jamMulai   = now()->setTime(8, 0);
        $batasHadir = now()->setTime(9, 0);
        $jamSelesai = now()->setTime(10, 0);

        if ($sekarang->between($jamMulai, $batasHadir)) {
            $statusDefault = 'hadir';
        } elseif ($sekarang->between($batasHadir, $jamSelesai)) {
            $statusDefault = 'terlambat';
        } else {
            $statusDefault = 'hadir';
        }

        return view('absensi.index', compact('statusDefault'));
    }

    // ===============================
    // DASHBOARD DOSEN
    // ===============================
    public function dashboardDosen()
    {

        $pertemuan = [];

        for ($i = 1; $i <= 12; $i++) {

             $jumlahHadir = Absensi::where('status', 'hadir')
                ->where('pertemuan_id', $i)
                ->count();

            $setting = PengaturanPresensi::where('pertemuan_id', $i)->latest()->first();

             $status = ($setting && $setting->is_open) ? 'open' : 'closed';

            $mode = $setting ? $setting->mode_kuliah : 'offline';
             $pertemuan[] = [
                 'id'     => $i,
                 'nama'   => "Pertemuan $i",
                 'status' => $status,
                 'mode'   => $mode, // ← TAMBAH INI
                 'hadir'  => $jumlahHadir
                ];
            }
        
    $mahasiswas = \App\Models\Mahasiswa::all();

    $absensis = Absensi::with('mahasiswa')->get();

    $presensiAktif = collect($pertemuan)
    ->where('status', 'open')
    ->count();

        return view('dosen.dashboard', compact(
        'pertemuan',
        'absensis',
        'mahasiswas',
        'presensiAktif'
        ));
    }

    public function detailPertemuan($id)
{
    $absensis = Absensi::with('mahasiswa')
        ->where('pertemuan_id', $id)
        ->latest()
        ->get();

    return view('dosen.dashboard', compact(
    'pertemuan',
    'absensis'
    ));
}

    // ===============================
// BUKA PRESENSI
// ===============================
public function bukaPresensi(Request $request)

{
    $request->validate([
        'mode_kuliah' => 'required|in:online,offline'
    ]);

    PengaturanPresensi::updateOrCreate(
        ['pertemuan_id' => $request->pertemuan_id],
        [
            'is_open'     => true,
            'mode_kuliah' => $request->mode_kuliah
        ]
    );

    return back()->with('success', '✅ Presensi berhasil dibuka');
    
}


    // ===============================
    // TUTUP PRESENSI
    // ===============================
    public function tutupPresensi(Request $request)
    {
      PengaturanPresensi::updateOrCreate(
        ['pertemuan_id' => $request->pertemuan_id],
        [
            'is_open' => false
        ]
      );

    return back()->with('success', '❌ Presensi berhasil ditutup');
    }
    // ===============================
    // SIMPAN ABSENSI MAHASISWA
    // ===============================
    public function store(Request $request)
{

    $mahasiswaId = $request->mahasiswa_id;
    $request->validate([
    'pertemuan_id' => 'required',
    'mahasiswa_id' => 'required'
]);

if (
    $request->status == 'izin' ||
    $request->status == 'sakit'
) {

    $request->validate([
        'bukti' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
    ]);
}

    // cek setting pertemuan
    $setting = PengaturanPresensi::where(
        'pertemuan_id',
        $request->pertemuan_id
    )->latest()->first();

    if (!$setting || !$setting->is_open) {

        return response()->json([
            'success' => false,
            'message' => 'Presensi ditutup'
        ], 403);
    }

    // cek apakah sudah absen
    $cek = Absensi::where('pertemuan_id', $request->pertemuan_id)
        ->where('mahasiswa_id', $mahasiswaId)
        ->exists();

    if ($cek) {

        return response()->json([
            'success' => false,
            'message' => 'Sudah absen'
        ], 403);
    }

    // validasi lokasi offline
    if (
    $setting->mode_kuliah == 'offline' &&
    $request->status == 'hadir'
) {

    $kampusLat = -6.966364659568048;
    $kampusLon = 107.6732810583995;

    //-6.966212567472055, 107.67345256536551
    //-6.966364659568048, 107.6732810583995
    
        $userLat = (float) $request->latitude;
        $userLon = (float) $request->longitude;

        if (!$userLat || !$userLon) {

            return response()->json([
                'success' => false,
                'message' => 'Lokasi belum diambil'
            ], 403);
        }

        // hitung jarak
        $earthRadius = 6371000;

        $dLat = deg2rad($userLat - $kampusLat);
        $dLon = deg2rad($userLon - $kampusLon);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($kampusLat)) *
            cos(deg2rad($userLat)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        

        \Log::info([
    'kampusLat' => $kampusLat,
    'kampusLon' => $kampusLon,
    'userLat' => $userLat,
    'userLon' => $userLon,
    'distance' => $distance
]);

        // maksimal jarak
        if ($distance >10000) {
        
        \Log::info([
    'distance' => $distance
]);

            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar area kampus'
            ], 403);
        }
    }

    $buktiPath = null;

if ($request->hasFile('bukti')) {

    $buktiPath = $request->file('bukti')
        ->store('bukti-absensi', 'public');
}

    // simpan absensi
    Absensi::create([
        'mahasiswa_id' => $mahasiswaId,
        'pertemuan_id' => $request->pertemuan_id,
        'status' => $request->status,
        'mode_kuliah' => $setting->mode_kuliah,
        'waktu' => now(),
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'bukti' => $buktiPath,
    ]);

    // response sukses
    return response()->json([
        'success' => true,
        'message' => 'Absensi berhasil'
    ]);
}
}