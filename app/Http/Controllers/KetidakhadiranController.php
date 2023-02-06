<?php

namespace App\Http\Controllers;

use App\Exports\KetidakhadiranFormatExport;
use App\Models\Ketidakhadiran;
use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class KetidakhadiranController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if (auth()->user()->role == 'admin') {

                if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                    $data_siswa_aktif = DB::table('siswa_aktif')
                        ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                        ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                        ->leftJoin('ketidakhadiran', function ($join) use ($filter) {
                            $join->on('siswa_aktif.id', 'ketidakhadiran.siswa_aktif_id')
                                ->where('ketidakhadiran.semester', $filter->semester);
                        })
                        ->where('siswa_aktif.kelas', $filter->kelas)
                        ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'ketidakhadiran.id as ketidakhadiran_id', 'ketidakhadiran.semester', 'ketidakhadiran.sakit', 'ketidakhadiran.izin', 'ketidakhadiran.tanpa_keterangan')
                        ->get();
                } else {
                    $data_siswa_aktif = [];
                }

                $data_siswa = SiswaAktif::all();

                $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                $data_semester = [1, 2];

                session(['ketidakhadiran-tahun_pelajaran' => $filter->tahun_pelajaran]);
                session(['ketidakhadiran-kelas' => $filter->kelas]);
                session(['ketidakhadiran-semester' => $filter->semester]);

                return view('admin.ketidakhadiran.index', compact('filter', 'setting', 'data_siswa', 'data_siswa_aktif', 'data_semester', 'data_angkatan'));
            } else {

                $wali_kelas = WaliKelas::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('guru_id', auth()->user()->guru->id)->get()->first();

                if ($wali_kelas) {
                    $kelas = $wali_kelas->kelas;

                    $semester = 1;

                    if ($filter->has('semester')) {
                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('ketidakhadiran', function ($join) use ($filter) {
                                $join->on('siswa_aktif.id', 'ketidakhadiran.siswa_aktif_id')
                                    ->where('ketidakhadiran.semester', $filter->semester);
                            })
                            ->where('siswa_aktif.kelas', $kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'ketidakhadiran.id as ketidakhadiran_id', 'ketidakhadiran.semester', 'ketidakhadiran.sakit', 'ketidakhadiran.izin', 'ketidakhadiran.tanpa_keterangan')
                            ->get();
                    } else {
                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('ketidakhadiran', function ($join) use ($filter) {
                                $join->on('siswa_aktif.id', 'ketidakhadiran.siswa_aktif_id')
                                    ->where('ketidakhadiran.semester', 1);
                            })
                            ->where('siswa_aktif.kelas', $kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'ketidakhadiran.id as ketidakhadiran_id', 'ketidakhadiran.semester', 'ketidakhadiran.sakit', 'ketidakhadiran.izin', 'ketidakhadiran.tanpa_keterangan')
                            ->get();
                    }

                    $data_semester = [1, 2];

                    session(['ketidakhadiran-tahun_pelajaran' => $setting->tahun_pelajaran]);
                    session(['ketidakhadiran-kelas' => $kelas]);
                    session(['ketidakhadiran-semester' => $filter->has('semester') ? $filter->semester : $semester]);

                    return view('admin.ketidakhadiran.index', compact('filter', 'setting', 'data_siswa_aktif', 'data_semester', 'kelas', 'semester'));
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
            'sakit' => 'required',
            'izin' => 'required',
            'tanpa_keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.ketidakhadiran')->with('error', 'Data ketidakhadiran gagal diperbarui');
        }

        $session_tahun_pelajaran = session()->get('ketidakhadiran-tahun_pelajaran');
        $session_semester = session()->get('ketidakhadiran-semester');

        Ketidakhadiran::create([
            'tahun_pelajaran' => $session_tahun_pelajaran,
            'semester' => $session_semester,
            'sakit' => $request->sakit,
            'izin' => $request->izin,
            'tanpa_keterangan' => $request->tanpa_keterangan,
            'siswa_aktif_id' => $siswa_aktif_id
        ]);

        return redirect()->back()->with('success', 'Data ketidakhadiran berhasil diperbarui');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sakit' => 'required',
            'izin' => 'required',
            'tanpa_keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.ketidakhadiran')->with('error', 'Data ketidakhadiran gagal diperbarui');
        }

        $ketidakhadiran = Ketidakhadiran::find($id);

        $ketidakhadiran->update([
            'sakit' => $request->sakit,
            'izin' => $request->izin,
            'tanpa_keterangan' => $request->tanpa_keterangan
        ]);

        return redirect()->back()->with('success', 'Data ketidakhadiran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $ketidakhadiran = Ketidakhadiran::find($id);

        $ketidakhadiran->delete();

        return redirect()->back()->with('success', 'Data ketidakhadiran berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\KetidakhadiranImport(), request()->file('data_ketidakhadiran'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data ketidakhadiran gagal diimport');
        }

        return redirect()->back()->with('success', 'Data ketidakhadiran berhasil diimport');
    }

    public function export_format()
    {
        $setting = Setting::all()->first();

        $wali_kelas = WaliKelas::where('guru_id', auth()->user()->guru->id)->where('tahun_pelajaran', $setting->tahun_pelajaran)->get()->first();

        return Excel::download(new KetidakhadiranFormatExport($wali_kelas->id), 'Data Ketidakhadiran '  . $wali_kelas->kelas .  '.xlsx');
    }
}
