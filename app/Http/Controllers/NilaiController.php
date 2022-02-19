<?php

namespace App\Http\Controllers;

use App\Exports\NilaiFormatExport;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    public function index()
    {
        $nilai = Nilai::all();
        $siswa = Siswa::all();
        $mapel = Mapel::all();

        $tahun_pelajaran = $siswa->unique('tahun_pelajaran')->values()->all();

        $kelas_x = $siswa->where('kelas', 'X')->unique('jurusan')->values()->all();
        $kelas_xi = $siswa->where('kelas', 'XI')->unique('jurusan')->values()->all();
        $kelas_xii = $siswa->where('kelas', 'XII')->unique('jurusan')->values()->all();

        $kelas = Nilai::where('guru_id', auth()->user()->guru->id)->get();

        // dd($kelas);

        return view('admin.nilai.index', compact('nilai', 'tahun_pelajaran', 'mapel', 'kelas_x', 'kelas_xi', 'kelas_xii'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nilai' => 'required',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.nilai')->with('error', 'Data nilai gagal diperbarui');
        }

        $nilai = Nilai::find($id);

        $nilai->update([
            'nilai' => $request->nilai,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.nilai')->with('success', 'Data nilai berhasil diperbarui');
    }

    public function destroy($id)
    {
        $nilai = Nilai::find($id);

        $nilai->delete();

        return redirect()->route('admin.nilai')->with('success', 'Data nilai berhasil dihapus');
    }

    public function import(Request $request)
    {
        try {
            Excel::import(new \App\Imports\NilaiImport($request->mapel), request()->file('data_nilai'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.nilai')->with('error', 'Data nilai gagal diimport');
        }

        return redirect()->route('admin.nilai')->with('success', 'Data nilai berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new NilaiFormatExport(), 'data-nilai-mutuharjo' . '.xlsx');
    }
}
