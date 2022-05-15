<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ketidakhadiran extends Model
{
    use HasFactory;

    protected $table = 'ketidakhadiran';
    protected $guarded = [];

    public function siswa_aktif()
    {
        return $this->belongsTo(SiswaAktif::class);
    }
}
