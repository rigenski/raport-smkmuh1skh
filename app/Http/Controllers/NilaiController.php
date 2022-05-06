<?php

namespace App\Http\Controllers;

use App\Exports\NilaiFormatExport;
use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
use App\Models\SiswaAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    public function index(Request $request)
    {

        if (auth()->user()->role == 'admin') {
            $filter = $request;

            $setting = Setting::all()[0];

            if ($filter->has('tahun_pelajaran') && $filter->has('mata_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                $mata_pelajaran_selected = MataPelajaran::where('nama_mata_pelajaran', $filter->mata_pelajaran)->get()[0];

                $siswa_aktif = DB::table('siswa_aktif')
                    ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                    ->leftJoin('nilai', function ($join) use ($filter) {
                        $join->on('siswa_aktif.id', 'nilai.siswa_aktif_id')
                            ->where('nilai.semester', $filter->semester);
                    })
                    ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', 'nilai.mata_pelajaran_id')
                    ->where('siswa_aktif.kelas', $filter->kelas)
                    ->select('siswa.nomer_induk_siswa', 'siswa.nama_siswa', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'nilai.id', 'nilai.semester', 'nilai.nilai', 'nilai.keterangan', 'nilai.status', 'mata_pelajaran.jenis_mata_pelajaran', 'mata_pelajaran.kode_mata_pelajaran', 'mata_pelajaran.nama_mata_pelajaran')
                    ->get();
            } else {
                $siswa_aktif = [];
            }

            $mata_pelajaran = DB::table('guru_mata_pelajaran')
                ->join('mata_pelajaran', 'guru_mata_pelajaran.mata_pelajaran_id', '=', 'mata_pelajaran.id')
                ->get();

            $semester = [1, 2];

            return view('admin.nilai.index', compact('filter', 'siswa_aktif', 'mata_pelajaran', 'semester'));
        } else {
            $filter = $request;

            $setting = Setting::all()[0];

            if ($filter->has('tahun_pelajaran') && $filter->has('mata_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                $mata_pelajaran_selected = MataPelajaran::where('nama_mata_pelajaran', $filter->mata_pelajaran)->get()[0];

                $siswa_aktif = DB::table('siswa_aktif')
                    ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                    ->leftJoin('nilai', 'siswa_aktif.id', 'nilai.siswa_aktif_id')
                    ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', 'nilai.mata_pelajaran_id')
                    ->where('siswa_aktif.kelas', $filter->kelas)
                    ->select('siswa.nomer_induk_siswa', 'siswa.nama_siswa', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'nilai.id', 'nilai.semester', 'nilai.nilai', 'nilai.keterangan', 'nilai.status', 'mata_pelajaran.jenis_mata_pelajaran', 'mata_pelajaran.kode_mata_pelajaran', 'mata_pelajaran.nama_mata_pelajaran')
                    ->get();
            } else {
                $siswa_aktif = [];
            }

            $mata_pelajaran = DB::table('guru_mata_pelajaran')
                ->join('mata_pelajaran', 'guru_mata_pelajaran.mata_pelajaran_id', '=', 'mata_pelajaran.id')
                ->where('guru_mata_pelajaran.guru_id', '=', auth()->user()->guru->id)
                ->get();

            $semester = [1, 2];

            return view('admin.nilai.index', compact('filter', 'siswa_aktif', 'mata_pelajaran', 'semester'));
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.nilai')->with('error', 'Data nilai gagal diperbarui');
        }

        $nilai = Nilai::find($id);

        $nilai->update([
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Data nilai berhasil diperbarui');
    }

    public function destroy($id)
    {
        $nilai = Nilai::find($id);

        $nilai->delete();

        return redirect()->back()->with('success', 'Data nilai berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\NilaiImport(), request()->file('data_nilai'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data nilai gagal diimport');
        }

        return redirect()->back()->with('success', 'Data nilai berhasil diimport');
    }

    public function export_format(Request $request)
    {
        return Excel::download(new NilaiFormatExport($request->guru_mata_pelajaran), 'data-nilai-mutuharjo' . '.xlsx');
    }
}
