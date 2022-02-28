<?php

namespace App\Imports;

use App\Models\Mapel;
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
        $siswa = Siswa::where('nis', $row[2])->get();

        if (auth()->user()->role == 'admin') {
            $guru_id = Mapel::find($this->mapel_id)->guru_id;

            return new Nilai([
                "tahun_pelajaran" => $row[0],
                "semester" => $row[1],
                "siswa_id" => $siswa[0]->id,
                "nilai" => $row[3],
                "keterangan" => $row[4],
                "mapel_id" => $this->mapel_id,
                "guru_id" => $guru_id
            ]);
        } else {
            return new Nilai([
                "tahun_pelajaran" => $row[0],
                "semester" => $row[1],
                "siswa_id" => $siswa[0]->id,
                "nilai" => $row[3],
                "keterangan" => $row[4],
                "mapel_id" => $this->mapel_id,
                "guru_id" => auth()->user()->guru->id
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
