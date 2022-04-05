<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\Mapel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MapelImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $guru = Guru::where('kode_guru', $row[2])->get();

        return new Mapel([
            "nama" => $row[0],
            "kelas" => $row[1],
            "guru_id" => $guru[0]->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
