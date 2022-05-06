<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class MataPelajaranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_mata_pelajaran = [
            [
                'jenis_mata_pelajaran' => 'KELOMPOK MATA PELAJARAN UMUM',
                'kode_mata_pelajaran' => 'PAB',
                'nama_mata_pelajaran' => 'Pendidikan Bahasa Arab',
            ],
            [
                'jenis_mata_pelajaran' => 'KELOMPOK MATA PELAJARAN UMUM',
                'kode_mata_pelajaran' => 'PAA',
                'nama_mata_pelajaran' => 'Pendidikan Aqidah Akhlak',
            ],
            [
                'jenis_mata_pelajaran' => 'KELOMPOK MATA PELAJARAN UMUM',
                'kode_mata_pelajaran' => 'IND',
                'nama_mata_pelajaran' => 'Bahasa Indonesia',
            ],
            [
                'jenis_mata_pelajaran' => 'KELOMPOK KEJURUAN',
                'kode_mata_pelajaran' => 'MTK',
                'nama_mata_pelajaran' => 'Matematika',
            ],
            [
                'jenis_mata_pelajaran' => 'KELOMPOK KEJURUAN',
                'kode_mata_pelajaran' => 'INFOR',
                'nama_mata_pelajaran' => 'Informatika',
            ],
        ];

        foreach ($data_mata_pelajaran as $mata_pelajaran) {
            $new_mata_pelajaran = new MataPelajaran();
            $new_mata_pelajaran->jenis_mata_pelajaran = $mata_pelajaran['jenis_mata_pelajaran'];
            $new_mata_pelajaran->kode_mata_pelajaran = $mata_pelajaran['kode_mata_pelajaran'];
            $new_mata_pelajaran->nama_mata_pelajaran = $mata_pelajaran['nama_mata_pelajaran'];
            $new_mata_pelajaran->save();
        }
    }
}
