<?php

namespace App\Http\Controllers;

use App\Exports\MapelFormatExport;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = Mapel::all();
        $guru = Guru::all();

        return view('admin.mapel.index', compact('mapel', 'guru'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data mapel gagal diperbarui');
        }

        $mapel = Mapel::find($id);

        $mapel->update([
            'nama' => $request->nama,
            'guru_id' => $request->guru,
        ]);

        return redirect()->back()->with('success', 'Data mapel berhasil diperbarui');
    }

    public function destroy($id)
    {
        $mapel = Mapel::find($id);

        $mapel->delete();

        return redirect()->back()->with('success', 'Data kelas berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\MapelImport, request()->file('data_mapel'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data kelas gagal diimport');
        }

        return redirect()->back()->with('success', 'Data kelas berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new MapelFormatExport(), 'data-mapel-mutuharjo' . '.xlsx');
    }
}
