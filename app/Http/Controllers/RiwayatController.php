<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Riwayat;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $riwayat = Riwayat::all();

        if ($filter->has('tahun_pelajaran')) {
            $wali_kelas = WaliKelas::where('tahun_pelajaran', $filter->tahun_pelajaran)->get();

            $nilai = DB::table('nilai')
                ->join('siswa_aktif', 'nilai.siswa_aktif_id', 'siswa_aktif.id')
                ->get();
        } else {
            $wali_kelas = [];
            $nilai = [];
        }

        $semester = [1, 2];

        return view('admin.riwayat.index', compact('filter', 'riwayat', 'wali_kelas', 'nilai', 'semester'));
    }
}
