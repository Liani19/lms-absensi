<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Mahasiswa::create([
        'nama' => 'Liani Siti',
        'nim' => '230001'
    ]);

    \App\Models\Mahasiswa::create([
        'nama' => 'Budi Santoso',
        'nim' => '230002'
    ]);

    \App\Models\Mahasiswa::create([
        'nama' => 'Rina Aulia',
        'nim' => '230003'
    ]);

    \App\Models\Mahasiswa::create([
        'nama' => 'Dika Pratama',
        'nim' => '230004'
    ]);
}
}
