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
        $setting = Setting::all()->first();

        $siswa_aktif = DB::table('siswa')
            ->join('siswa_aktif', 'siswa.id', '=', 'siswa_aktif.siswa_id')
            ->where('siswa.nis', '=', $row[3])
            ->get()->first();

        $mata_pelajaran = MataPelajaran::where('kode', $row[0])->get()->first();

        $data_nilai = Nilai::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $row[7])->where('siswa_aktif_id', $siswa_aktif->id)->where('mata_pelajaran_id', $mata_pelajaran->id)->get();

        if (count($data_nilai)) {
            foreach ($data_nilai as $nilai) {
                $nilai->delete();
            }
        }
        
        if(($row[7] == '1' || $row[7] == '2' ) && is_numeric($row[5])) {
         return new Nilai([
            "tahun_pelajaran" => $setting->tahun_pelajaran,
            "semester" => $row[7],
            "nilai" => $row[5],
            "keterangan" => $row[6],
            "siswa_aktif_id" => $siswa_aktif->id,
            "mata_pelajaran_id" => $mata_pelajaran->id,
        ]);   
        } else {
            return redirect()-back();
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
