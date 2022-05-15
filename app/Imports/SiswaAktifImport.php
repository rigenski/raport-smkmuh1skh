<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\SiswaAktif;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SiswaAktifImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $siswa = Siswa::where('nis', $row[1])->get()->first();

        $angkatan = explode(' ',  $row[3])[0];

        return new SiswaAktif([
            "tahun_pelajaran" => $row[0],
            "kelas" => $row[3],
            "angkatan" => $angkatan,
            "jurusan" => $row[4],
            "siswa_id" => $siswa->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
