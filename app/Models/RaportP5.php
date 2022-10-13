<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaportP5 extends Model
{
    use HasFactory;

    protected $table = 'raport_p5';
    protected $guarded = [];

    public function raport_p5_projek()
    {
        return $this->BelongsTo(RaportP5Projek::class);
    }

}
