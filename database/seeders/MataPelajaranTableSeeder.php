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
                'jenis' => 'KELOMPOK MATA PELAJARAN UMUM',
                'nama' => 'Pendidikan Bahasa Arab',
                'kode_mapel' => 'PAB',
            ],
            [
                'jenis' => 'KELOMPOK MATA PELAJARAN UMUM',
                'nama' => 'Pendidikan Aqidah Akhlak',
                'kode_mapel' => 'PAA',
            ],
            [
                'jenis' => 'KELOMPOK MATA PELAJARAN UMUM',
                'nama' => 'Bahasa Indonesia',
                'kode_mapel' => 'IND',
            ],
            [
                'jenis' => 'KELOMPOK KEJURUAN',
                'nama' => 'Matematika',
                'kode_mapel' => 'MTK',
            ],
            [
                'jenis' => 'KELOMPOK KEJURUAN',
                'nama' => 'Informatika',
                'kode_mapel' => 'INFOR',
            ],
        ];

        foreach ($data_mata_pelajaran as $mata_pelajaran) {
            $new_mata_pelajaran = new MataPelajaran();
            $new_mata_pelajaran->jenis = $mata_pelajaran['jenis'];
            $new_mata_pelajaran->kode_mapel = $mata_pelajaran['kode_mapel'];
            $new_mata_pelajaran->nama = $mata_pelajaran['nama'];
            $new_mata_pelajaran->save();
        }
    }
}
