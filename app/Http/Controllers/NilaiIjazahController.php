<?php

namespace App\Http\Controllers;

use App\Exports\NilaiIjazahFormatExport;
use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiIjazah;
use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class NilaiIjazahController extends Controller
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
                            ->leftJoin('nilai_ijazah', function ($join) use ($filter, $mata_pelajaran) {
                                $join->on('siswa_aktif.id', 'nilai_ijazah.siswa_aktif_id')
                                    ->where('nilai_ijazah.mata_pelajaran_id', $mata_pelajaran->id);
                            })
                            ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', 'nilai_ijazah.mata_pelajaran_id')
                            ->where('siswa_aktif.kelas', $filter->kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'nilai_ijazah.id as nilai_id',  'nilai_ijazah.nilai', 'nilai_ijazah.status as status_nilai', 'mata_pelajaran.id as mapel_id', 'mata_pelajaran.jenis as jenis_mapel', 'mata_pelajaran.kode as kode_mapel', 'mata_pelajaran.nama as nama_mapel')
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

                return view('admin.nilai-ijazah.index', compact('filter', 'setting', 'data_siswa', 'data_siswa_aktif', 'data_mata_pelajaran', 'data_angkatan', 'mata_pelajaran'));
            } else {
                $wali_kelas = WaliKelas::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('guru_id', auth()->user()->guru->id)->get()->first();

                if ($filter->has('mata_pelajaran')) {
                    $mata_pelajaran = MataPelajaran::where('nama', $filter->mata_pelajaran)->get()->first();

                    if ($mata_pelajaran) {
                        $data_siswa_aktif = DB::table('siswa_aktif')
                            ->where('siswa_aktif.tahun_pelajaran', $setting->tahun_pelajaran)
                            ->leftJoin('siswa', 'siswa.id', 'siswa_aktif.siswa_id')
                            ->leftJoin('nilai_ijazah', function ($join) use ($filter, $mata_pelajaran) {
                                $join->on('siswa_aktif.id', 'nilai_ijazah.siswa_aktif_id')
                                    ->where('nilai_ijazah.mata_pelajaran_id', $mata_pelajaran->id);
                            })
                            ->leftJoin('mata_pelajaran', 'mata_pelajaran.id', 'nilai_ijazah.mata_pelajaran_id')
                            ->where('siswa_aktif.kelas', $wali_kelas->kelas)
                            ->select('siswa.id as siswa_id', 'siswa.nis', 'siswa.nama as nama_siswa', 'siswa_aktif.id as siswa_aktif_id', 'siswa_aktif.tahun_pelajaran', 'siswa_aktif.kelas', 'siswa_aktif.angkatan', 'siswa_aktif.jurusan', 'nilai_ijazah.id as nilai_id', 'nilai_ijazah.nilai', 'nilai_ijazah.status as status_nilai', 'mata_pelajaran.id as mapel_id', 'mata_pelajaran.jenis as jenis_mapel', 'mata_pelajaran.kode as kode_mapel', 'mata_pelajaran.nama as nama_mapel')
                            ->get();
                    } else {
                        return redirect()->back()->with('error', 'Data mata pelajaran tidak ada');
                    }
                } else {
                    $data_siswa_aktif = [];
                    $mata_pelajaran = null;
                }

                $data_mata_pelajaran = MataPelajaran::all();

                session(['nilai-tahun_pelajaran' => $setting->tahun_pelajaran]);
                session(['nilai-kelas' => $wali_kelas->kelas]);
                session(['nilai-mata_pelajaran' => $mata_pelajaran]);

                return view('admin.nilai-ijazah.index', compact('filter', 'setting', 'data_siswa_aktif', 'data_mata_pelajaran', 'mata_pelajaran', 'wali_kelas'));
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function store(Request $request, $siswa_aktif_id, $mata_pelajaran_id)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.nilai')->with('error', 'Data nilai ijazah gagal diperbarui');
        }

        $session_tahun_pelajaran = session()->get('nilai-tahun_pelajaran');

        NilaiIjazah::create([
            "tahun_pelajaran" => $session_tahun_pelajaran,
            "nilai" => $request->nilai,
            "siswa_aktif_id" => $siswa_aktif_id,
            "mata_pelajaran_id" => $mata_pelajaran_id,
        ]);

        return redirect()->back()->with('success', 'Data nilai ijazah berhasil diperbarui');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.nilai')->with('error', 'Data nilai ijazah gagal diperbarui');
        }

        $nilai = NilaiIjazah::find($id);

        $nilai->update([
            'nilai' => $request->nilai,
        ]);

        return redirect()->back()->with('success', 'Data nilai ijazah berhasil diperbarui');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\NilaiIjazahmport(), request()->file('data_nilai'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data nilai ijazah gagal diimport');
        }

        return redirect()->back()->with('success', 'Data nilai ijazah berhasil diimport');
    }

    public function export_format(Request $request)
    {
        $kelas = session()->get('nilai-kelas');
        $mata_pelajaran = MataPelajaran::find($request->mata_pelajaran);

        return Excel::download(new NilaiIjazahFormatExport($kelas, $mata_pelajaran), 'Simaku - Data Nilai ijazah Ijazah ' . $kelas . '-' . $mata_pelajaran->nama . '.xlsx');
    }
}
