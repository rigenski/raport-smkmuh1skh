<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportP5Dimensi extends Model
{
    use HasFactory;

    protected $table = 'raport_p5_dimensi';
    protected $guarded = [];

    public function raport_p5_projek()
    {
        return $this->belongsTo(RaportP5Projek::class);
    }

    public function raport_p5_elemen()
    {
        return $this->hasMany(RaportP5Elemen::class);
    }
}
