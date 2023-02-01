<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\GuruRaportP5;
use App\Models\MataPelajaran;
use App\Models\WaliKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruRaportP5Import implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $guru = Guru::where('kode', $row[3])->get();

        return new GuruRaportP5([
            "tahun_pelajaran" => $row[0],
            "semester" => $row[1],
            "kelas" => $row[2],
            "guru_id" => $guru[0]->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
