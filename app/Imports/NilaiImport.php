<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaAktif;
use Illuminate\Support\Facades\DB;
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

        $siswa_aktif = DB::table('siswa')
            ->join('siswa_aktif', 'siswa.id', '=', 'siswa_aktif.siswa_id')
            ->where('siswa.nomer_induk_siswa', '=', $row[3])
            ->get()[0];

        $mata_pelajaran = MataPelajaran::where('kode_mata_pelajaran', $row[0])->get()[0];

        return new Nilai([
            "tahun_pelajaran" => $setting->tahun_pelajaran,
            "semester" => $row[7],
            "nilai" => $row[5],
            "keterangan" => $row[6],
            "siswa_aktif_id" => $siswa_aktif->id,
            "mata_pelajaran_id" => $mata_pelajaran->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
