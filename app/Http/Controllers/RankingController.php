<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        if ($request->has('tipe') && $request->has('angkatan') &&  $request->has('semester')) {
            $siswa_filter = Siswa::where('kelas', $request->angkatan)->get();

            $siswa = [];

            foreach ($siswa_filter as $data) {
                $total_nilai = 0;
                $mapel = [];

                foreach ($data->nilai->where('semester', $request->semester) as $nilai) {
                    $total_nilai += $nilai->nilai;

                    array_push($mapel, [$nilai->mapel->nama => $nilai->nilai]);
                }


                array_push($siswa, ['nis' => $data->nis, 'nama' => $data->nama, 'kelas' => $data->kelas, 'jurusan' => $data->jurusan, 'total_nilai' => $total_nilai, 'mapel' => $mapel]);
            }
        } else {
            $siswa = [];
        }

        $data_siswa = Siswa::all();
        $data_nilai = Nilai::all();

        $kelas_x = $data_siswa->where('kelas', 'X')->unique('jurusan')->values()->toArray();
        $kelas_xi = $data_siswa->where('kelas', 'XI')->unique('jurusan')->values()->toArray();
        $kelas_xii = $data_siswa->where('kelas', 'XII')->unique('jurusan')->values()->toArray();

        $jurusan = $data_siswa->unique('jurusan')->values()->toArray();

        $kelas = array_merge($kelas_x, $kelas_xi);
        $kelas = array_merge($kelas, $kelas_xii);

        $kelas = json_encode($kelas);
        $jurusan = json_encode($jurusan);

        $semester = [1, 2, 3, 4, 5, 6];

        $siswa = json_encode($siswa);

        return view('admin.ranking.index', compact('kelas', 'jurusan', 'semester', 'filter', 'siswa'));
    }
}
