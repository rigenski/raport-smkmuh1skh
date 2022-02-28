<?php

namespace App\Http\Controllers;

use App\Exports\WaliKelasFormatExport;
use App\Models\Guru;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WaliKelasController extends Controller
{
    public function index()
    {
        $wali_kelas = WaliKelas::all();
        $guru = Guru::all();

        return view('admin.wali-kelas.index', compact('wali_kelas', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data wali kelas gagal diperbarui');
        }

        $wali_kelas = WaliKelas::find($id);

        $wali_kelas->update([
            'kelas' => $request->kelas,
            'guru_id' => $request->guru,
        ]);

        return redirect()->back()->with('success', 'Data wali kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $wali_kelas = WaliKelas::find($id);

        $wali_kelas->guru->user->update([
            'role' => 'guru'
        ]);

        $wali_kelas->delete();

        return redirect()->back()->with('success', 'Data wali kelas berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\WaliKelasImport, request()->file('data_wali_kelas'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data wali kelas gagal diimport');
        }

        return redirect()->back()->with('success', 'Data wali kelas berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new WaliKelasFormatExport(), 'data-wali_kelas-mutuharjo' . '.xlsx');
    }
}
