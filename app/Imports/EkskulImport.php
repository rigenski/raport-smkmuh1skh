<?php

namespace App\Imports;

use App\Models\Ekskul;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class EkskulImport implements ToModel, WithStartRow
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

        $data_ekskul = Ekskul::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $row[5])->where('siswa_aktif_id', $siswa_aktif->id)->get();

        if (count($data_ekskul)) {
            foreach ($data_ekskul as $ekskul) {
                $ekskul->delete();
            }
        }

        return new Ekskul([
            "tahun_pelajaran" => $setting->tahun_pelajaran,
            "semester" => $row[5],
            "nama" => $row[3],
            "keterangan" => $row[4],
            "siswa_aktif_id" => $siswa_aktif->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
