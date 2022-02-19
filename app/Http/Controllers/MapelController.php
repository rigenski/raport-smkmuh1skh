<?php

namespace App\Http\Controllers;

use App\Exports\MapelFormatExport;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = Mapel::all();

        return view('admin.mapel.index', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'kode_guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.mapel')->with('error', 'Data mapel gagal diperbarui');
        }

        $mapel = Mapel::find($id);

        $mapel->update([
            'nama' => $request->nama,
            'kode_guru' => $request->kode_guru,
        ]);

        return redirect()->route('admin.mapel')->with('success', 'Data mapel berhasil diperbarui');
    }

    public function destroy($id)
    {
        $mapel = Mapel::find($id);

        $mapel->delete();

        return redirect()->route('admin.mapel')->with('success', 'Data kelas berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\MapelImport, request()->file('data_mapel'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.mapel')->with('error', 'Data kelas gagal diimport');
        }

        return redirect()->route('admin.mapel')->with('success', 'Data kelas berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new MapelFormatExport(), 'data-mapel-mutuharjo' . '.xlsx');
    }
}
