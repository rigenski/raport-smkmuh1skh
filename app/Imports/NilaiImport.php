<?php

namespace App\Imports;

use App\Models\Nilai;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NilaiImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function __construct($mapel_id)
    {
        $this->mapel_id = $mapel_id;
    }

    public function model(array $row)
    {
        $siswa = Siswa::where('nis', $row[1])->get();

        return new Nilai([
            "semester" => $row[0],
            "siswa_id" => $siswa[0]->id,
            "nilai" => $row[2],
            "keterangan" => $row[3],
            "mapel_id" => $this->mapel_id,
            "guru_id" => auth()->user()->guru->id
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
