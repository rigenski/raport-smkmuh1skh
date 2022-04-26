<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting();
        $setting->tahun_pelajaran = "2021/2022";
        $setting->sekolah = "SMK Muhammadiyah 1 Sukoharjo";
        $setting->kepala_sekolah = "Drs. Bambang Sahana, M.Pd";
        $setting->alamat = "Jl. Anggrek No. 2 Sukoharjo";
        $setting->npsn = "20310439";
        $setting->logo = "logo-smk-mutuharjo.png";
        $setting->save();
    }
}
