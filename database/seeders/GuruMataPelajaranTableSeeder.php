<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

class GuruMataPelajaranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_guru_mata_pelajaran = [
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'NB',
                'kode_mapel' => 'PAB',
                'kelas' => 'X RPL 1',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'NB',
                'kode_mapel' => 'PAB',
                'kelas' => 'X TKJ 1',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'NC',
                'kode_mapel' => 'PAA',
                'kelas' => 'X RPL 1',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'ND',
                'kode_mapel' => 'IND',
                'kelas' => 'X RPL 1',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'ND',
                'kode_mapel' => 'IND',
                'kelas' => 'X TKJ 1',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'NE',
                'kode_mapel' => 'INFOR',
                'kelas' => 'X RPL 1',
            ],
        ];

        foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran) {
            $guru = Guru::where('kode_guru', $guru_mata_pelajaran['kode_guru'])->get();
            $mata_pelajaran = MataPelajaran::where('kode_mapel', $guru_mata_pelajaran['kode_mapel'])->get();

            $new_guru = new GuruMataPelajaran();
            $new_guru->tahun_pelajaran = $guru_mata_pelajaran['tahun_pelajaran'];
            $new_guru->kelas = $guru_mata_pelajaran['kelas'];
            $new_guru->guru_id = $guru[0]->id;
            $new_guru->mata_pelajaran_id = $mata_pelajaran[0]->id;
            $new_guru->save();
        }
    }
}
