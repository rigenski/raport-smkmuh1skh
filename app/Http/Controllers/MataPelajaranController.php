<?php

namespace App\Http\Controllers;

use App\Exports\MataPelajaranFormatExport;
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

        $setting = Setting::all()->first();

        if ($setting) {

            if ($filter->has('jenis')) {
                $data_mata_pelajaran = MataPelajaran::where('jenis', $filter->jenis)->orderBy('urutan', 'ASC')->get();
            } else {
                $data_mata_pelajaran = MataPelajaran::orderBy('urutan', 'ASC')->get();
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }

        $data_jenis_mata_pelajaran = MataPelajaran::all()->unique('jenis')->values()->all();

        return view('admin.mata-pelajaran.index', compact('filter', 'data_mata_pelajaran', 'data_jenis_mata_pelajaran'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'kode' => 'required',
            'nama' => 'required',
            'urutan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data mata pelajaran gagal diperbarui');
        }

        $mata_pelajaran = MataPelajaran::find($id);

        $mata_pelajaran->update([
            'jenis' => $request->jenis,
            'kode' => $request->kode,
            'nama' => $request->nama,
            'urutan' => $request->urutan,
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
