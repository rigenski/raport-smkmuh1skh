<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Siswa([
            "tahun_pelajaran" => $row[0],
            "nis" => $row[1],
            "nama" => $row[2],
            "kelas" => $row[3],
            "jurusan" => $row[4],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
