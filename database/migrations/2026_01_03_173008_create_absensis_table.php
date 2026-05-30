<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            // ===============================
            // DATA MAHASISWA
            // ===============================
            $table->unsignedBigInteger('user_id');

            // ===============================
            // STATUS KEHADIRAN
            // ===============================
            $table->enum('status', ['hadir','terlambat','izin','sakit']);

            // ===============================
            // WAKTU ABSENSI
            // ===============================
            $table->timestamp('waktu');

            // ===============================
            // LOKASI GPS
            // ===============================
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();

            // ===============================
            // BUKTI ABSENSI ONLINE
            // ===============================
            $table->string('bukti')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};