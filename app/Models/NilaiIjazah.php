<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiIjazah extends Model
{
    use HasFactory;

    protected $table = 'nilai_ijazah';
    protected $guarded = [];

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function siswa_aktif()
    {
        return $this->belongsTo(SiswaAktif::class);
    }
}
