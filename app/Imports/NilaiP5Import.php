<?php

namespace App\Imports;

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

        $siswa_aktif = DB::table('siswa')
            ->join('siswa_aktif', 'siswa.id', '=', 'siswa_aktif.siswa_id')
            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
            ->where('siswa.nis', '=', $row[1])
            ->get()->first();

        $raport_p5_dimensi_data = RaportP5Dimensi::all();
        $raport_p5_elemen_data = RaportP5Elemen::all();

        if(count($raport_p5_dimensi_data)) {
            $index = 0;
            foreach($raport_p5_dimensi_data as $raport_p5_dimensi) {
                foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen) {
                    $data_nilai = NilaiP5::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $row[count($row) - 1])->where('siswa_aktif_id', $siswa_aktif->id)->where('raport_p5_elemen_id', $raport_p5_elemen->id)->get();
                    
                    
                    if (count($data_nilai)) {
                        foreach ($data_nilai as $nilai) {
                            $nilai->delete();
                        }
                    }
    
    
                    if($row[count($row) - 1] == '1' || $row[count($row) - 1] == '2' ) {
                        if($index !== count($raport_p5_elemen_data)) {
                            NilaiP5::create([
                                "tahun_pelajaran" => $setting->tahun_pelajaran,
                                "semester" => $row[count($row) - 1],
                                "nilai" => $row[3 + $index],
                                "siswa_aktif_id" => $siswa_aktif->id,
                                "raport_p5_elemen_id" => $raport_p5_elemen->id,
                            ]);   

                            $index++;
                        } else {
                            return new NilaiP5([
                                "tahun_pelajaran" => $setting->tahun_pelajaran,
                                "semester" => $row[count($row) - 1],
                                "nilai" => $row[3 + $index],
                                "siswa_aktif_id" => $siswa_aktif->id,
                                "raport_p5_elemen_id" => $raport_p5_elemen->id,
                            ]);   
                        }
                    } else {
                        return redirect()->back();
                    }
                }
            }
        } else {
            return redirect()->back();
        }

        
    }

    public function startRow(): int
    {
        return 2;
    }
}
