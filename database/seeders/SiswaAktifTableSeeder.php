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
                'nis' => '12233',
                'nama' => 'Adam Fahrizal Harsono',
                'tahun_pelajaran' => '2021/2022',
                'kelas' => 'X RPL 1',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'nis' => '11862',
                'nama' => 'Alif Ridwan Rasyidin',
                'tahun_pelajaran' => '2021/2022',
                'kelas' => 'X RPL 1',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'nis' => '11863',
                'nama' => 'Ardiansyah Putra Permana',
                'tahun_pelajaran' => '2021/2022',
                'kelas' => 'X RPL 1',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
            ],
            [
                'nis' => '11864',
                'nama' => 'Atlantis Cartenzian Arkadya',
                'tahun_pelajaran' => '2021/2022',
                'kelas' => 'X TKJ 1',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            ],
            [
                'nis' => '11865',
                'nama' => 'Attariq Anugrah Ramadhani',
                'tahun_pelajaran' => '2021/2022',
                'kelas' => 'X TKJ 1',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            ],
            [
                'nis' => '11866',
                'nama' => 'Avin Fajar Fitriansyah',
                'tahun_pelajaran' => '2021/2022',
                'kelas' => 'X TKJ 1',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi',
            ],
        ];

        foreach ($data_siswa_aktif as $siswa_aktif) {
            $siswa = Siswa::where('nis', $siswa_aktif['nis'])->get();

            $angkatan = explode(' ',  $siswa_aktif['kelas'])[0];

            $new_siswa_aktif = new SiswaAktif();
            $new_siswa_aktif->tahun_pelajaran = $siswa_aktif['tahun_pelajaran'];
            $new_siswa_aktif->nis = $siswa_aktif['nis'];
            $new_siswa_aktif->kelas = $siswa_aktif['kelas'];
            $new_siswa_aktif->angkatan = $angkatan;
            $new_siswa_aktif->jurusan = $siswa_aktif['jurusan'];
            $new_siswa_aktif->siswa_id = $siswa[0]->id;
            $new_siswa_aktif->save();
        }
    }
}
