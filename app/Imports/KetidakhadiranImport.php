<?php

namespace App\Imports;

use App\Models\Ekskul;
use App\Models\Ketidakhadiran;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class KetidakhadiranImport implements ToModel, WithStartRow
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
            ->where('siswa.nis', '=', $row[1])
            ->get()->first();

        $data_ketidakhadiran = Ketidakhadiran::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $row[5])->where('siswa_aktif_id', $siswa_aktif->id)->get();

        if (count($data_ketidakhadiran)) {
            foreach ($data_ketidakhadiran as $ketidakhadiran) {
                $ketidakhadiran->delete();
            }
        }

        return new Ketidakhadiran([
            "tahun_pelajaran" => $setting->tahun_pelajaran,
            "semester" => $row[6],
            "sakit" => $row[3],
            "izin" => $row[4],
            "tanpa_keterangan" => $row[5],
            "siswa_aktif_id" => $siswa_aktif->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
