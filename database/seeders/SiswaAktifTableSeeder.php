<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\SiswaAktif;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiswaAktifTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data_siswa_aktif = [
            [
                'tahun_pelajaran' => '2021/2022',
                'nomer_induk_siswa' => '12233',
                'nama' => 'Adam Fahrizal Harsono',
                'kelas' => 'X RPL 1',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'nomer_induk_siswa' => '11862',
                'nama' => 'Alif Ridwan Rasyidin',
                'kelas' => 'X RPL 1',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'nomer_induk_siswa' => '11863',
                'nama' => 'Ardiansyah Putra Permana',
                'kelas' => 'X RPL 1',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'nomer_induk_siswa' => '11864',
                'nama' => 'Atlantis Cartenzian Arkadya',
                'kelas' => 'X TKJ 1',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'nomer_induk_siswa' => '11865',
                'nama' => 'Attariq Anugrah Ramadhani',
                'kelas' => 'X TKJ 1',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            ],
            [
                'tahun_pelajaran' => '2021/2022',
                'nomer_induk_siswa' => '11866',
                'nama' => 'Avin Fajar Fitriansyah',
                'kelas' => 'X TKJ 1',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            ],
        ];

        foreach ($data_siswa_aktif as $siswa_aktif) {
            $siswa = Siswa::where('nomer_induk_siswa', $siswa_aktif['nomer_induk_siswa'])->get();

            $angkatan = explode(' ',  $siswa_aktif['kelas'])[0];

            $new_siswa_aktif = new SiswaAktif();
            $new_siswa_aktif->tahun_pelajaran = $siswa_aktif['tahun_pelajaran'];
            $new_siswa_aktif->kelas = $siswa_aktif['kelas'];
            $new_siswa_aktif->angkatan = $angkatan;
            $new_siswa_aktif->jurusan = $siswa_aktif['jurusan'];
            $new_siswa_aktif->siswa_id = $siswa[0]->id;
            $new_siswa_aktif->save();
        }
    }
}
