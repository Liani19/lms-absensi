<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;

Route::get('/pertemuan', [AbsensiController::class, 'pertemuan']);
Route::get('/absensi', [AbsensiController::class, 'index']);
Route::post('/absensi', [AbsensiController::class, 'store']);
Route::get('/dosen', [AbsensiController::class, 'dashboardDosen']);
Route::post('/dosen/buka-presensi', [AbsensiController::class, 'bukaPresensi']);

Route::post('/dosen/tutup-presensi', [AbsensiController::class, 'tutupPresensi']);