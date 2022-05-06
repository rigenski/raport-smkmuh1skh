<?php

namespace App\Http\Controllers;

use App\Exports\MataPelajaranFormatExport;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        if ($filter->has('jenis_mata_pelajaran')) {
            $mata_pelajaran = MataPelajaran::where('jenis_mata_pelajaran', $filter->jenis_mata_pelajaran)->get();
        } else {
            $mata_pelajaran = MataPelajaran::all();
        }

        $jenis_mata_pelajaran = MataPelajaran::all()->unique('jenis_mata_pelajaran')->values()->all();

        return view('admin.mata-pelajaran.index', compact('filter', 'mata_pelajaran', 'jenis_mata_pelajaran'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis_mata_pelajaran' => 'required',
            'kode_mata_pelajaran' => 'required',
            'nama_mata_pelajaran' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data mata pelajaran gagal diperbarui');
        }

        $mata_pelajaran = MataPelajaran::find($id);

        $mata_pelajaran->update([
            'jenis_mata_pelajaran' => $request->jenis_mata_pelajaran,
            'kode_mata_pelajaran' => $request->kode_mata_pelajaran,
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
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

    public function export_format()
    {
        return Excel::download(new MataPelajaranFormatExport(), 'data-mata_pelajaran-mutuharjo' . '.xlsx');
    }
}
