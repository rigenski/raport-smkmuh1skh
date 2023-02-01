<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruRaportP5 extends Model
{
    use HasFactory;

    protected $table = 'guru_raport_p5';
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
