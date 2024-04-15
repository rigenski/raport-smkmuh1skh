<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $guarded = [];

    public function guru_mata_pelajaran()
    {
        return $this->hasMany(GuruMataPelajaran::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function nilai_ijazah()
    {
        return $this->hasMany(NilaiIjazah::class);
    }
}
