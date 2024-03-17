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
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if (auth()->user()->role == 'admin') {

                if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('mata_pelajaran') && $filter->has('semester')) {
                    $mata_pelajaran = MataPelajaran::where('nama', $filter->mata_pelajaran)->get()->first();

                    if ($mata_pelajaran) {

                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $filter->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('nilai', function ($join) use ($filter, $mata_pelajaran) {
                                $join->on('siswa_aktif.id', 'nilai.siswa_aktif_id')
                                    ->where('nilai.semester', $filter->semester)
                                    ->where('nilai.mata_pelajaran_id', $mata_pelajaran->id);
                            })
                            ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', 'nilai.mata_pelajaran_id')
                            ->where('siswa_aktif.kelas', $filter->kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'nilai.id as nilai_id', 'nilai.semester', 'nilai.nilai', 'nilai.keterangan as keterangan_nilai', 'nilai.status as status_nilai', 'mata_pelajaran.id as mapel_id', 'mata_pelajaran.jenis as jenis_mapel', 'mata_pelajaran.kode as kode_mapel', 'mata_pelajaran.nama as nama_mapel')
                            ->get();
                    } else {
                        return redirect()->back()->with('error', 'Data mata pelajaran tidak ada');
                    }
                } else {
                    $data_siswa_aktif = [];
                    $mata_pelajaran = null;
                }


                $data_siswa = SiswaAktif::all();

                $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                $data_mata_pelajaran = DB::table('guru_mata_pelajaran')
                    ->join('mata_pelajaran', 'guru_mata_pelajaran.mata_pelajaran_id', '=', 'mata_pelajaran.id')
                    ->get();

                $data_semester = [1, 2];

                session(['nilai-tahun_pelajaran' => $filter->tahun_pelajaran]);
                session(['nilai-semester' => $filter->semester]);

                return view('admin.nilai.index', compact('filter', 'setting', 'data_siswa', 'data_siswa_aktif', 'data_mata_pelajaran', 'data_semester', 'data_angkatan', 'mata_pelajaran'));
            } else {

                if ($filter->has('kelas') && $filter->has('mata_pelajaran') && $filter->has('semester')) {

                    $mata_pelajaran = MataPelajaran::where('nama', $filter->mata_pelajaran)->get()->first();

                    if ($mata_pelajaran) {

                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('nilai', function ($join) use ($filter, $mata_pelajaran) {
                                $join->on('siswa_aktif.id', 'nilai.siswa_aktif_id')
                                    ->where('nilai.semester', $filter->semester)
                                    ->where('nilai.mata_pelajaran_id', $mata_pelajaran->id);
                            })
                            ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', 'nilai.mata_pelajaran_id')
                            ->where('siswa_aktif.kelas', $filter->kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'nilai.id as nilai_id', 'nilai.semester', 'nilai.nilai', 'nilai.keterangan as keterangan_nilai', 'nilai.status as status_nilai', 'mata_pelajaran.id as mapel_id', 'mata_pelajaran.jenis as jenis_mapel', 'mata_pelajaran.kode as kode_mapel', 'mata_pelajaran.nama as nama_mapel')
                            ->get();
                    } else {
                        return redirect()->back()->with('error', 'Data mata pelajaran tidak ada');
                    }
                } else {
                    $data_siswa_aktif = [];
                    $mata_pelajaran = null;
                }

                $data_mata_pelajaran = DB::table('guru_mata_pelajaran')
                    ->join('mata_pelajaran', 'guru_mata_pelajaran.mata_pelajaran_id', '=', 'mata_pelajaran.id')
                    ->where('guru_mata_pelajaran.guru_id', '=', auth()->user()->guru->id)
                    ->orderBy('urutan', 'ASC')
                    ->get();

                $data_semester = [1, 2];

                session(['nilai-tahun_pelajaran' => $setting->tahun_pelajaran]);
                session(['nilai-semester' => $filter->semester]);

                return view('admin.nilai.index', compact('filter', 'setting', 'data_siswa_aktif', 'data_mata_pelajaran', 'data_semester', 'mata_pelajaran'));
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function store(Request $request, $siswa_aktif_id, $mata_pelajaran_id)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.nilai')->with('error', 'Data nilai gagal diperbarui');
        }

        $session_tahun_pelajaran = session()->get('nilai-tahun_pelajaran');
        $session_semester = session()->get('nilai-semester');

        Nilai::create([
            "tahun_pelajaran" => $session_tahun_pelajaran,
            "semester" => $session_semester,
            "nilai" => $request->nilai,
            "keterangan" => $request->keterangan,
            "siswa_aktif_id" => $siswa_aktif_id,
            "mata_pelajaran_id" => $mata_pelajaran_id,
        ]);

        return redirect()->back()->with('success', 'Data nilai berhasil diperbarui');
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
        $guru_mata_pelajaran = GuruMataPelajaran::find($request->guru_mata_pelajaran);

        return Excel::download(new NilaiFormatExport($request->guru_mata_pelajaran), 'Simaku - Data Nilai ' . $guru_mata_pelajaran->kelas . '-' . $guru_mata_pelajaran->mata_pelajaran->nama . '.xlsx');
    }
}
