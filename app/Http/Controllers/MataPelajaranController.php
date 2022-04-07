<?php

namespace App\Http\Controllers;

use App\Exports\MataPelajaranFormatExport;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()[0];

        if ($request->has('tahun_pelajaran')) {
            $mata_pelajaran = MataPelajaran::where('tahun_pelajaran', $filter->tahun_pelajaran)->get();
        } else {
            $mata_pelajaran = MataPelajaran::where('tahun_pelajaran', $setting->tahun_pelajaran)->get();
        }

        $guru = Guru::all();

        $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

        $guru = Guru::all();

        return view('admin.mata_pelajaran.index', compact('filter', 'setting', 'mata_pelajaran', 'guru', 'tahun_pelajaran'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'guru' => 'required',
            'kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data mata pelajaran gagal diperbarui');
        }

        $mata_pelajaran = MataPelajaran::find($id);

        $mata_pelajaran->update([
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'guru_id' => $request->guru,
        ]);

        return redirect()->back()->with('success', 'Data mata pelajaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $mata_pelajaran = MataPelajaran::find($id);

        $mata_pelajaran->delete();

        return redirect()->back()->with('success', 'Data mata pelajaran berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\MataPelajaranImport, request()->file('data_mata_pelajaran'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data mata pelajaran gagal diimport');
        }

        return redirect()->back()->with('success', 'Data mata pelajaran berhasil diimport');
    }

    public function reset()
    {
        MataPelajaran::truncate();

        return redirect()->back()->with('success', 'Data mata pelajaran berhasil direset');
    }

    public function export_format()
    {
        return Excel::download(new MataPelajaranFormatExport(), 'data-mata_pelajaran-mutuharjo' . '.xlsx');
    }
}
