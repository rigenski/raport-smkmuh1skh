<?php

namespace App\Http\Controllers;

use App\Exports\SiswaFormatExport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::all();

        return view('admin.siswa.index', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required',
            'nama' => 'required',
            'jurusan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data siswa gagal diperbarui');
        }

        $siswa = Siswa::find($id);

        $siswa->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        $siswa->delete();

        return redirect()->back()->with('success', 'Data siswa berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\SiswaImport, request()->file('data_siswa'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data siswa gagal diimport');
        }

        return redirect()->back()->with('success', 'Data siswa berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new SiswaFormatExport(), 'data-siswa-mutuharjo' . '.xlsx');
    }
}
