<?php

namespace App\Http\Controllers;

use App\Exports\NilaiFormatExport;
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
            $filter = $request;

            $nilai = [];

            if ($filter->has('tahun_pelajaran') && $filter->has('mapel') && $filter->has('semester') && $filter->has('kelas')) {
                $siswa = Siswa::where('kelas', $filter->kelas)->get();

                if (count($siswa)) {
                    foreach ($siswa as $data) {
                        $nilai_selected =  Nilai::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('mapel', $filter->mapel)->where('siswa_id', $data->id)->get();

                        if (count($nilai_selected)) {
                            array_push($nilai, $nilai_selected[0]);
                        }
                    }
                }
            }

            $tahun_pelajaran = Nilai::all()->unique('tahun_pelajaran')->values()->all();

            $mapel = Mapel::all()->unique('nama')->values()->all();

            $semester = [1, 2, 3, 4, 5, 6];

            $kelas = Siswa::all()->unique('kelas')->values()->all();

            return view('admin.nilai.index', compact('filter', 'nilai', 'tahun_pelajaran', 'mapel', 'semester', 'kelas'));
        } else {
            $filter = $request;

            $nilai = [];

            if ($filter->has('tahun_pelajaran') && $filter->has('mapel') && $filter->has('semester') && $filter->has('kelas')) {
                $siswa = Siswa::where('kelas', $filter->kelas)->get();

                if (count($siswa)) {
                    foreach ($siswa as $data) {
                        $nilai_selected =  Nilai::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('mapel', $filter->mapel)->where('siswa_id', $data->id)->get();

                        if (count($nilai_selected)) {
                            array_push($nilai, $nilai_selected[0]);
                        }
                    }
                }
            }

            $tahun_pelajaran = Nilai::all()->unique('tahun_pelajaran')->values()->all();

            $mapel = auth()->user()->guru->mapel;

            $semester = [1, 2, 3, 4, 5, 6];

            $kelas = Siswa::all()->unique('kelas')->values()->all();

            return view('admin.nilai.index', compact('filter', 'nilai', 'tahun_pelajaran', 'mapel', 'semester', 'kelas'));
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
