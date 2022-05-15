<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaAktif extends Model
{
    use HasFactory;

    protected $table = 'siswa_aktif';
    protected $guarded = [];

    public function siswa()
    {
        return $this->BelongsTo(Siswa::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function ekskul()
    {
        return $this->hasMany(Ekskul::class);
    }

    public function ketidakhadiran()
    {
        return $this->hasMany(Ketidakhadiran::class);
    }
}
