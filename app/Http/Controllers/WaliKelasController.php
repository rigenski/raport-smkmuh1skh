<?php

namespace App\Http\Controllers;

use App\Exports\WaliKelasFormatExport;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WaliKelasController extends Controller
{
    public function index()
    {
        $wali_kelas = WaliKelas::all();

        return view('admin.wali-kelas.index', compact('wali_kelas'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'kode_guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.wali_kelas')->with('error', 'Data wali kelas gagal diperbarui');
        }

        $wali_kelas = WaliKelas::find($id);

        $wali_kelas->update([
            'kelas' => $request->kelas,
            'kode_guru' => $request->kode_guru,
        ]);

        return redirect()->route('admin.wali_kelas')->with('success', 'Data wali kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $wali_kelas = WaliKelas::find($id);

        $wali_kelas->delete();

        return redirect()->route('admin.wali_kelas')->with('success', 'Data wali kelas berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\WaliKelasImport, request()->file('data_wali_kelas'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.wali_kelas')->with('error', 'Data wali kelas gagal diimport');
        }

        return redirect()->route('admin.wali_kelas')->with('success', 'Data wali kelas berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new WaliKelasFormatExport(), 'data-wali_kelas-mutuharjo' . '.xlsx');
    }
}
