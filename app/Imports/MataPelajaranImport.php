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
            "jenis_mata_pelajaran" => $row[0],
            "kode_mata_pelajaran" => $row[1],
            "nama_mata_pelajaran" => $row[2],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
