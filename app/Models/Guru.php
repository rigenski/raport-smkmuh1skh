<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wali_kelas()
    {
        return $this->hasOne(WaliKelas::class);
    }

    public function guru_mata_pelajaran()
    {
        return $this->hasMany(GuruMataPelajaran::class);
    }
}
