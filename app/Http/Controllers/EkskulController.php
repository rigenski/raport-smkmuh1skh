<?php

namespace App\Http\Controllers;

use App\Exports\EkskulFormatExport;
use App\Models\Ekskul;
use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EkskulController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if (auth()->user()->role == 'admin') {

                if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                    $data_siswa_aktif = DB::table('siswa_aktif')
                        ->where('siswa_aktif.tahun_pelajaran', $filter->tahun_pelajaran)
                        ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                        ->leftJoin('ekskul', function ($join) use ($filter) {
                            $join->on('siswa_aktif.id', 'ekskul.siswa_aktif_id')
                                ->where('ekskul.semester', $filter->semester);
                        })
                        ->where('siswa_aktif.kelas', $filter->kelas)
                        ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'ekskul.id as ekskul_id', 'ekskul.semester', 'ekskul.nama as nama_ekskul', 'ekskul.keterangan as keterangan_ekskul')
                        ->get();
                } else {
                    $data_siswa_aktif = [];
                }

                $data_siswa = SiswaAktif::all();

                $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                $data_semester = [1, 2];

                session(['ekskul-tahun_pelajaran' => $filter->tahun_pelajaran]);
                session(['ekskul-semester' => $filter->semester]);

                return view('admin.ekskul.index', compact('filter', 'setting', 'data_siswa', 'data_siswa_aktif', 'data_semester', 'data_angkatan'));
            } else {

                $wali_kelas = WaliKelas::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('guru_id', auth()->user()->guru->id)->get()->first();

                if ($wali_kelas) {
                    $kelas = $wali_kelas->kelas;

                    $semester = 1;

                    if ($filter->has('semester')) {
                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('ekskul', function ($join) use ($filter) {
                                $join->on('siswa_aktif.id', 'ekskul.siswa_aktif_id')
                                    ->where('ekskul.semester', $filter->semester);
                            })
                            ->where('siswa_aktif.kelas', $kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'ekskul.id as ekskul_id', 'ekskul.semester', 'ekskul.nama as nama_ekskul', 'ekskul.keterangan as keterangan_ekskul')
                            ->get();
                    } else {
                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('ekskul', function ($join) use ($filter) {
                                $join->on('siswa_aktif.id', 'ekskul.siswa_aktif_id')
                                    ->where('ekskul.semester', 1);
                            })
                            ->where('siswa_aktif.kelas', $kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'ekskul.id as ekskul_id', 'ekskul.semester', 'ekskul.nama as nama_ekskul', 'ekskul.keterangan as keterangan_ekskul')
                            ->get();
                    }

                    $data_semester = [1, 2];

                    session(['ekskul-tahun_pelajaran' => $setting->tahun_pelajaran]);
                    session(['ekskul-semester' => $filter->has('semester') ? $filter->semester : $semester]);

                    return view('admin.ekskul.index', compact('filter', 'setting', 'data_siswa_aktif', 'data_semester', 'kelas', 'semester'));
                } else {
                    return redirect()->route('admin.wali_kelas')->with('error', 'Isi data wali kelas terlebih dahulu');
                }
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function store(Request $request, $siswa_aktif_id)
    {
        $validator = Validator::make($request->all(), [
            'ekskul' => 'required',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.ekskul')->with('error', 'Data ekskul gagal diperbarui');
        }

        $session_tahun_pelajaran = session()->get('ekskul-tahun_pelajaran');
        $session_semester = session()->get('ekskul-semester');

        Ekskul::create([
            'tahun_pelajaran' => $session_tahun_pelajaran,
            'semester' => $session_semester,
            'nama' => $request->ekskul,
            'keterangan' => $request->keterangan,
            'siswa_aktif_id' => $siswa_aktif_id
        ]);

        return redirect()->back()->with('success', 'Data ekskul berhasil diperbarui');
    }

    public function update(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'ekskul' => 'required',
        //     'keterangan' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->route('admin.ekskul')->with('error', 'Data ekskul gagal diperbarui');
        // }

        $ekskul = Ekskul::find($id);

        $ekskul->update([
            'nama' => $request->ekskul,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Data ekskul berhasil diperbarui');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\EkskulImport(), request()->file('data_ekskul'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data ekskul gagal diimport');
        }

        return redirect()->back()->with('success', 'Data ekskul berhasil diimport');
    }

    public function export_format()
    {
        $setting = Setting::all()->first();

        $wali_kelas = WaliKelas::where('guru_id', auth()->user()->guru->id)->where('tahun_pelajaran', $setting->tahun_pelajaran)->get()->first();

        return Excel::download(new EkskulFormatExport($wali_kelas->id), 'Simaku - Data Ekskul ' . $wali_kelas->kelas . '.xlsx');
    }
}
