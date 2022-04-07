<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';
    protected $guarded = [];

    public function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
