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
        $guru = Guru::where('kode_guru', $row[3])->get();

        return new MataPelajaran([
            "tahun_pelajaran" => $row[0],
            "nama" => $row[1],
            "kelas" => $row[2],
            "guru_id" => $guru[0]->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
