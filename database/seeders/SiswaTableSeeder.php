<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_siswa = [
            [
                'nomer_induk_siswa' => '12233',
                'nama_siswa' => 'Adam Fahrizal Harsono',
            ],
            [
                'nomer_induk_siswa' => '11862',
                'nama_siswa' => 'Alif Ridwan Rasyidin',
            ],
            [
                'nomer_induk_siswa' => '11863',
                'nama_siswa' => 'Ardiansyah Putra Permana',
            ],
            [
                'nomer_induk_siswa' => '11864',
                'nama_siswa' => 'Atlantis Cartenzian Arkadya',
            ],
            [
                'nomer_induk_siswa' => '11865',
                'nama_siswa' => 'Attariq Anugrah Ramadhani',
            ],
            [
                'nomer_induk_siswa' => '11866',
                'nama_siswa' => 'Avin Fajar Fitriansyah',
            ],
            [
                'nomer_induk_siswa' => '11867',
                'nama_siswa' => 'Bayu Sahid Ramadhan',
            ],
            [
                'nomer_induk_siswa' => '11868',
                'nama_siswa' => 'Danudiraja Soenoto',
            ],
            [
                'nomer_induk_siswa' => '11869',
                'nama_siswa' => 'Edwin Maulana Bachtiar',
            ],
        ];

        foreach ($data_siswa as $siswa) {
            $new_siswa = new Siswa();
            $new_siswa->nomer_induk_siswa = $siswa['nomer_induk_siswa'];
            $new_siswa->nama_siswa = $siswa['nama_siswa'];
            $new_siswa->save();
        }
    }
}
