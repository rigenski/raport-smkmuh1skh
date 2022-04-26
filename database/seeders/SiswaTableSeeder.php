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
                'nis' => '12233',
                'nama' => 'Adam Fahrizal Harsono',
            ],
            [
                'nis' => '11862',
                'nama' => 'Alif Ridwan Rasyidin',
            ],
            [
                'nis' => '11863',
                'nama' => 'Ardiansyah Putra Permana',
            ],
            [
                'nis' => '11864',
                'nama' => 'Atlantis Cartenzian Arkadya',
            ],
            [
                'nis' => '11865',
                'nama' => 'Attariq Anugrah Ramadhani',
            ],
            [
                'nis' => '11866',
                'nama' => 'Avin Fajar Fitriansyah',
            ],
            [
                'nis' => '11867',
                'nama' => 'Bayu Sahid Ramadhan',
            ],
            [
                'nis' => '11868',
                'nama' => 'Danudiraja Soenoto',
            ],
            [
                'nis' => '11869',
                'nama' => 'Edwin Maulana Bachtiar',
            ],
        ];

        foreach ($data_siswa as $siswa) {
            $new_siswa = new Siswa();
            $new_siswa->nis = $siswa['nis'];
            $new_siswa->nama = $siswa['nama'];
            $new_siswa->save();
        }
    }
}
