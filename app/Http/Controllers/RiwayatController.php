<?php

namespace App\Http\Controllers;

use App\Models\Riwayat;
use App\Models\Setting;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {
            $data_riwayat = Riwayat::all();

            if ($filter->has('tahun_pelajaran')) {
                $data_wali_kelas = WaliKelas::where('tahun_pelajaran', $filter->tahun_pelajaran)->get();

                $data_nilai = DB::table('nilai')
                    ->join('siswa_aktif', 'nilai.siswa_aktif_id', 'siswa_aktif.id')
                    ->get();
            } else {
                $data_wali_kelas = WaliKelas::where('tahun_pelajaran', $setting->tahun_pelajaran)->get();

                $data_nilai = DB::table('nilai')
                    ->join('siswa_aktif', 'nilai.siswa_aktif_id', 'siswa_aktif.id')
                    ->get();
            }

            $data_semester = [1, 2];

            return view('admin.riwayat.index', compact('filter', 'data_riwayat', 'data_wali_kelas', 'data_nilai', 'data_semester', 'setting'));
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }
}
