<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use App\Models\NilaiIjazah;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NilaiIjazahmport implements ToModel, WithStartRow
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
            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
            ->where('siswa.nis', '=', $row[3])
            ->get()->first();

        $mata_pelajaran = MataPelajaran::where('kode', $row[0])->get()->first();

        $data_nilai = NilaiIjazah::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('siswa_aktif_id', $siswa_aktif->id)->where('mata_pelajaran_id', $mata_pelajaran->id)->get();

        if (count($data_nilai)) {
            foreach ($data_nilai as $nilai) {
                $nilai->delete();
            }
        }

        if (is_numeric($row[5])) {
            return new NilaiIjazah([
                "tahun_pelajaran" => $setting->tahun_pelajaran,
                "nilai" => $row[5],
                "siswa_aktif_id" => $siswa_aktif->id,
                "mata_pelajaran_id" => $mata_pelajaran->id,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
