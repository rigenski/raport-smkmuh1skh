<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiP5 extends Model
{
    use HasFactory;

    protected $table = 'nilai_p5';
    protected $guarded = [];

    public function raport_p5_elemen()
    {
        return $this->belongsTo(RaportP5Elemen::class);
    }

    public function siswa_aktif()
    {
        return $this->belongsTo(SiswaAktif::class);
    }
}
