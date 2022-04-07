<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Riwayat;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $riwayat = Riwayat::all();

        $kelas = [];
        $nilai = [];

        if ($filter->has('tahun_pelajaran')) {
            $kelas = WaliKelas::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->unique('kelas')->values()->all();
            $nilai = Nilai::where('tahun_pelajaran', $filter->tahun_pelajaran)->get();
        }

        $semester = [1, 2];

        $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

        return view('admin.riwayat.index', compact('filter', 'riwayat', 'kelas', 'nilai', 'semester', 'tahun_pelajaran'));
    }
}
