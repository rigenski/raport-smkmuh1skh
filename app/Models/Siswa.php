<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $guarded = [];

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }
}
