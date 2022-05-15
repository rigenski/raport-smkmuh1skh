<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\WaliKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruMataPelajaranImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $mata_pelajaran = MataPelajaran::where('kode', $row[2])->get()->first();
        $guru = Guru::where('kode', $row[4])->get();

        return new GuruMataPelajaran([
            "tahun_pelajaran" => $row[0],
            "kelas" => $row[1],
            "mata_pelajaran_id" => $mata_pelajaran->id,
            "guru_id" => $guru[0]->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
