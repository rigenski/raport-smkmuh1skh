<?php

namespace App\Http\Controllers;

use App\Exports\SiswaAktifFormatExport;
use App\Models\Setting;
use App\Models\SiswaAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SiswaAktifController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()[0];

        if ($filter->has('tahun_pelajaran') && $filter->has('kelas')) {
            $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
        } else {
            $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->get();
        }

        $kelas = SiswaAktif::all()->unique('kelas')->values()->all();

        return view('admin.siswa-aktif.index', compact('filter', 'siswa_aktif', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'jurusan' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data siswa aktif gagal diperbarui');
        }

        $siswa_aktif = SiswaAktif::find($id);

        $angkatan = explode(' ',  $request->kelas)[0];

        $siswa_aktif->update([
            'kelas' => $request->kelas,
            'angkatan' => $angkatan,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->back()->with('success', 'Data siswa aktif berhasil diperbarui');
    }

    public function destroy($id)
    {
        $siswa_aktif = SiswaAktif::find($id);

        $siswa_aktif->delete();

        return redirect()->back()->with('success', 'Data siswa aktif berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\SiswaAktifImport, request()->file('data_siswa_aktif'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data siswa aktif gagal diimport');
        }

        return redirect()->back()->with('success', 'Data siswa aktif berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new SiswaAktifFormatExport(), 'data-siswa-aktif-mutuharjo' . '.xlsx');
    }
}
