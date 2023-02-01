<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportP5Projek extends Model
{
    use HasFactory;

    protected $table = 'raport_p5_projek';
    protected $guarded = [];

    public function raport_p5()
    {
        return $this->BelongsTo(RaportP5::class);
    }

    public function raport_p5_dimensi()
    {
        return $this->hasMany(RaportP5Dimensi::class);
    }
}
