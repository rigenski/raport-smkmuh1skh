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

        if ($filter->has('jenis')) {
            $mata_pelajaran = MataPelajaran::where('jenis', $filter->jenis)->get();
        } else {
            $mata_pelajaran = MataPelajaran::all();
        }

        $jenis_mapel = MataPelajaran::all()->unique('jenis')->values()->all();

        return view('admin.mata-pelajaran.index', compact('filter', 'mata_pelajaran', 'jenis_mapel'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'kode_mapel' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data mata pelajaran gagal diperbarui');
        }

        $mata_pelajaran = MataPelajaran::find($id);

        $mata_pelajaran->update([
            'jenis' => $request->jenis,
            'kode_mapel' => $request->kode_mapel,
            'nama' => $request->nama,
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
