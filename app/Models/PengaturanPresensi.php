<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanPresensi extends Model
{
    protected $fillable = [
    'is_open',
    'mode_kuliah',
    'pertemuan_id' // 🔥 TAMBAH INI
];
}