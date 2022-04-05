<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapelNilai extends Model
{
    use HasFactory;

    protected $table = 'mapel_nilai';
    protected $guarded = [];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function nilai()
    {
        return $this->belongsTo(Nilai::class);
    }
}
