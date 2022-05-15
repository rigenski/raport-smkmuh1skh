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
        $mata_pelajaran = MataPelajaran::all()->last();

        return new MataPelajaran([
            "jenis" => $row[0],
            "kode" => $row[1],
            "nama" => $row[2],
            "urutan" => $mata_pelajaran ? $mata_pelajaran->id + 1 : 1,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
