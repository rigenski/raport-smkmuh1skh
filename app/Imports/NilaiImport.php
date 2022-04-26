<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaAktif;
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

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('nis', $row[4])->get()[0];

        $mata_pelajaran = MataPelajaran::where('kode_mapel', $row[1])->get()[0];

        return new Nilai([
            "tahun_pelajaran" => $setting->tahun_pelajaran,
            "semester" => $row[8],
            "nilai" => $row[6],
            "keterangan" => $row[7],
            "kelas" => $siswa_aktif->kelas,
            "angkatan" => $siswa_aktif->angkatan,
            "jurusan" => $siswa_aktif->jurusan,
            "siswa_aktif_id" => $siswa_aktif->id,
            "mata_pelajaran_id" => $mata_pelajaran->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
