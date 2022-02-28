<?php

namespace App\Http\Controllers;

use App\Exports\NilaiFormatExport;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    public function index(Request $request)
    {

        if (auth()->user()->role == 'admin') {
            $data_nilai = Nilai::all();
            $data_siswa = Siswa::all();

            $filter = $request;

            $tahun_pelajaran = $data_nilai->unique('tahun_pelajaran')->values()->all();

            if ($request->has('tahun_pelajaran') && $request->has('mapel') && $request->has('semester') && $request->has('kelas')) {
                $kelas = explode(' ', $request->kelas);

                $siswa_selected = Siswa::where('kelas', $kelas[0])->where('jurusan', $kelas[1])->get();

                $nilai = [];
                $mapel_filter = Mapel::find($request->mapel);

                $guru_id = Mapel::find($request->mapel)->guru_id;

                foreach ($siswa_selected as $data) {
                    $nilai_filter =  Nilai::where('guru_id', $guru_id)->where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->where('mapel_id', $request->mapel)->where('siswa_id', $data->id)->get();

                    if (count($nilai_filter)) {
                        array_push($nilai, $nilai_filter[0]);
                    }
                }
            } else {
                $nilai = [];
                $mapel_filter = null;
            }


            $kelas_x = $data_siswa->where('kelas', 'X')->unique('jurusan')->values()->all();
            $kelas_xi = $data_siswa->where('kelas', 'XI')->unique('jurusan')->values()->all();
            $kelas_xii = $data_siswa->where('kelas', 'XII')->unique('jurusan')->values()->all();

            $semester = [1, 2, 3, 4, 5, 6];
            $mapel = Mapel::all();

            return view('admin.nilai.index', compact('nilai', 'tahun_pelajaran', 'kelas_x', 'kelas_xi', 'kelas_xii', 'filter', 'semester', 'mapel_filter', 'mapel'));
        } else {
            $data_nilai = Nilai::all();
            $data_siswa = Siswa::all();

            if ($request->has('tahun_pelajaran') && $request->has('mapel') && $request->has('semester') && $request->has('kelas')) {
                $kelas = explode(' ', $request->kelas);

                $siswa_selected = Siswa::where('kelas', $kelas[0])->where('jurusan', $kelas[1])->get();

                $nilai = [];
                $mapel_filter = Mapel::find($request->mapel);

                foreach ($siswa_selected as $data) {
                    $nilai_filter =  Nilai::where('guru_id', auth()->user()->guru->id)->where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->where('mapel_id', $request->mapel)->where('siswa_id', $data->id)->get();

                    if (count($nilai_filter)) {
                        array_push($nilai, $nilai_filter[0]);
                    }
                }
            } else {
                $nilai = [];
                $mapel_filter = null;
            }

            $filter = $request;

            $tahun_pelajaran = $data_nilai->unique('tahun_pelajaran')->values()->all();


            $kelas_x = $data_siswa->where('kelas', 'X')->unique('jurusan')->values()->all();
            $kelas_xi = $data_siswa->where('kelas', 'XI')->unique('jurusan')->values()->all();
            $kelas_xii = $data_siswa->where('kelas', 'XII')->unique('jurusan')->values()->all();

            $semester = [1, 2, 3, 4, 5, 6];

            return view('admin.nilai.index', compact('nilai', 'tahun_pelajaran', 'kelas_x', 'kelas_xi', 'kelas_xii', 'filter', 'semester', 'mapel_filter'));
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

    public function import(Request $request)
    {
        try {
            Excel::import(new \App\Imports\NilaiImport($request->mapel), request()->file('data_nilai'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data nilai gagal diimport');
        }

        return redirect()->back()->with('success', 'Data nilai berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new NilaiFormatExport(), 'data-nilai-mutuharjo' . '.xlsx');
    }
}
