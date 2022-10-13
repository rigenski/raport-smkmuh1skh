<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportP5Elemen extends Model
{
    use HasFactory;

    protected $table = 'raport_p5_elemen';
    protected $guarded = [];

    public function raport_p5_dimensi()
    {
        return $this->hasMany(RaportP5Dimensi::class);
    }
}
