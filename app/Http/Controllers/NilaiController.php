<?php

namespace App\Http\Controllers;

use App\Exports\NilaiFormatExport;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
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

            $setting = Setting::all()[0];

            $siswa = [];

            if ($filter->has('tahun_pelajaran') && $filter->has('mata_pelajaran') && $filter->has('semester') && $filter->has('kelas')) {
                $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('kelas', $filter->kelas)->where('mata_pelajaran', $filter->mata_pelajaran)->unique('siswa_id')->values()->all();
            }


            $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

            $mata_pelajaran = MataPelajaran::all();

            $nilai = Nilai::all();

            $semester = [1, 2];

            return view('admin.nilai.index', compact('filter', 'nilai', 'siswa', 'tahun_pelajaran', 'mata_pelajaran', 'semester'));
        } else {
            $filter = $request;

            $setting = Setting::all()[0];

            $siswa = [];

            if ($filter->has('tahun_pelajaran') && $filter->has('mata_pelajaran') && $filter->has('semester') && $filter->has('kelas')) {
                $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('kelas', $filter->kelas)->where('mata_pelajaran', $filter->mata_pelajaran)->unique('siswa_id')->values()->all();
            }

            $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

            $mata_pelajaran = auth()->user()->guru->mata_pelajaran;

            $semester = [1, 2];

            return view('admin.nilai.index', compact('filter', 'setting', 'siswa', 'tahun_pelajaran', 'mata_pelajaran', 'semester'));
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

    public function export_format()
    {
        return Excel::download(new NilaiFormatExport(), 'data-nilai-mutuharjo' . '.xlsx');
    }
}
