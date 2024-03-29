<?php

namespace App\Http\Controllers;

use App\Exports\GuruMataPelajaranFormatExport;
use App\Models\Guru;
use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GuruMataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if ($request->has('tahun_pelajaran')) {
                $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $filter->tahun_pelajaran)->get();
            } else {
                $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $setting->tahun_pelajaran)->get();
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }

        $data_guru = Guru::all();
        $data_mata_pelajaran = MataPelajaran::all();

        return view('admin.guru-mata-pelajaran.index', compact('filter', 'setting', 'data_guru_mata_pelajaran', 'data_mata_pelajaran', 'data_guru'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'mata_pelajaran' => 'required',
            'guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data guru mata pelajaran gagal diperbarui');
        }

        $guru_mata_pelajaran = GuruMataPelajaran::find($id);

        $guru_mata_pelajaran->update([
            'kelas' => $request->kelas,
            'mata_pelajaran_id' => $request->mata_pelajaran,
            'guru_id' => $request->guru,
        ]);

        return redirect()->back()->with('success', 'Data guru mata pelajaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $guru_mata_pelajaran = GuruMataPelajaran::find($id);

        $guru_mata_pelajaran->delete();

        return redirect()->back()->with('success', 'Data guru mata pelajaran berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\GuruMataPelajaranImport, request()->file('data_guru_mata_pelajaran'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data guru mata pelajaran gagal diimport');
        }

        return redirect()->back()->with('success', 'Data guru mata pelajaran berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new GuruMataPelajaranFormatExport(), 'Simaku - Guru Mata Pelajaran' . '.xlsx');
    }
}
