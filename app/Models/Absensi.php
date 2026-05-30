<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
    'status',
    'mode_kuliah',
    'waktu',
    'bukti',
    'latitude',
    'longitude',
    'pertemuan_id',
    'mahasiswa_id'
];

public function mahasiswa()
{
    return $this->belongsTo(Mahasiswa::class);
}

}