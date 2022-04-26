<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MataPelajaranImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new MataPelajaran([
            "jenis" => $row[0],
            "kode_mapel" => $row[1],
            "pelajaran" => $row[2],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
