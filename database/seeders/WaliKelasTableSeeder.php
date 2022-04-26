<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Seeder;

class WaliKelasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_wali_kelas = [
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'NB',
                'nama' => 'Sukardi, S.Ag.',
                'kelas' => 'X RPL 1',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'kode_guru' => 'NC',
                'nama' => 'Luqman Hakim, S.Pd.',
                'kelas' => 'X TKJ 1',
            ],
        ];

        foreach ($data_wali_kelas as $wali_kelas) {
            $guru = Guru::where('kode_guru', $wali_kelas['kode_guru'])->get();

            $guru[0]->user->update([
                'role' => 'wali kelas',
            ]);

            $new_guru = new WaliKelas();
            $new_guru->tahun_pelajaran = $wali_kelas['tahun_pelajaran'];
            $new_guru->kelas = $wali_kelas['kelas'];
            $new_guru->guru_id = $guru[0]->id;
            $new_guru->save();
        }
    }
}
