<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
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

    public function model(array $row)
    {
        $setting = Setting::all()[0];

        $siswa = Siswa::where('nis', $row[1])->get()[0];

        $mata_pelajaran = MataPelajaran::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('nama', $row[4])->where('kelas', $row[3])->get()[0];

        $angkatan = explode(' ', $row[3])[0];

        return new Nilai([
            "tahun_pelajaran" => $setting->tahun_pelajaran,
            "semester" => $row[0],
            "nilai" => $row[5],
            "keterangan" => $row[6],
            "kelas" => $row[3],
            "angkatan" => $angkatan,
            "jurusan" => $siswa->jurusan,
            "mata_pelajaran" => $row[4],
            "siswa_id" => $siswa->id,
            "mata_pelajaran_id" => $mata_pelajaran->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
