<?php

namespace App\Http\Controllers;

use App\Exports\KelasFormatExport;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();

        return view('admin.kelas.index', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.kelas')->with('error', 'Data kelas gagal diperbarui');
        }

        $kelas = Kelas::find($id);

        $kelas->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('admin.kelas')->with('success', 'Data kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        $kelas->delete();

        return redirect()->route('admin.kelas')->with('success', 'Data kelas berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\KelasImport, request()->file('data_kelas'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.kelas')->with('error', 'Data kelas gagal diimport');
        }

        return redirect()->route('admin.kelas')->with('success', 'Data kelas berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new KelasFormatExport(), 'data-kelas-mutuharjo' . '.xlsx');
    }
}
