<?php

namespace App\Imports;

use App\Models\RaportP5;
use App\Models\RaportP5Projek;
use App\Models\RaportP5Dimensi;
use App\Models\RaportP5Elemen;
use App\Models\NilaiP5;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaAktif;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NilaiP5Import implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $setting = Setting::all()->first();

        $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $row[0])->get()->last();

        $siswa_aktif = DB::table('siswa')
            ->join('siswa_aktif', 'siswa.id', '=', 'siswa_aktif.siswa_id')
            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
            ->where('siswa.nis', '=', $row[1])
            ->get()->first();
            
        $raport_p5 = RaportP5::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $row[count($row) - 1])->first();

        if($raport_p5) {
            $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
        } else {
            $data_raport_p5_projek = [];
        }

        $count_elemen = 0;

        foreach($data_raport_p5_projek as $raport_p5_projek) {
            foreach($raport_p5_projek->raport_p5_dimensi as $raport_p5_dimensi) {
                $count_elemen += count($raport_p5_dimensi->raport_p5_elemen);
            }
        }


        $index = 0;

        foreach($data_raport_p5_projek as $raport_p5_projek) {
            foreach($raport_p5_projek->raport_p5_dimensi as $raport_p5_dimensi) {
                foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen) {
                    $data_nilai = NilaiP5::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $row[count($row) - 1])->where('siswa_aktif_id', $siswa_aktif->id)->where('raport_p5_elemen_id', $raport_p5_elemen->id)->get();
                    
                    if (count($data_nilai)) {
                        foreach ($data_nilai as $nilai) {
                            $nilai->delete();
                        }
                    }

                    if($row[0] && $row[1] && $row[2]) {
                        NilaiP5::create([
                            "tahun_pelajaran" => $setting->tahun_pelajaran,
                            "semester" => $row[count($row) - 1],
                            "nilai" => $row[3 + $index],
                            "siswa_aktif_id" => $siswa_aktif->id,
                            "raport_p5_elemen_id" => $raport_p5_elemen->id,
                        ]);   
                    }
                }
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
