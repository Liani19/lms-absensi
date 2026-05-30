<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Absensi;

class Mahasiswa extends Model
{
   public function absensis()
{
    return $this->hasMany(Absensi::class);
} //
}
