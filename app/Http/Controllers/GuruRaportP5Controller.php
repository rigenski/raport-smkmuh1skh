<?php

namespace App\Http\Controllers;

use App\Exports\GuruRaportP5FormatExport;
use App\Models\Guru;
use App\Models\GuruRaportP5;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GuruRaportP5Controller extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if ($request->has('tahun_pelajaran') && $filter->has('semester')) {
                $data_guru_raport_p5 = GuruRaportP5::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->get();
            } else {
                $data_guru_raport_p5 = [];
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }

        $data_guru = Guru::all();
        
        $data_semester = [1, 2];

        return view('admin.guru-raport-p5.index', compact('filter', 'setting', 'data_guru_raport_p5', 'data_guru', 'data_semester'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'semester' => 'required',
            'guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data guru raport p5 gagal diperbarui');
        }

        $guru_raport_p5 = GuruRaportP5::find($id);

        $guru_raport_p5->update([
            'kelas' => $request->kelas,
            'semester' => $request->semester,
            'guru_id' => $request->guru,
        ]);

        return redirect()->back()->with('success', 'Data guru raport p5 berhasil diperbarui');
    }

    public function destroy($id)
    {
        $guru_raport_p5 = GuruRaportP5::find($id);

        $guru_raport_p5->delete();

        return redirect()->back()->with('success', 'Data guru raport p5 berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\GuruRaportP5Import, request()->file('data_guru_raport_p5'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data guru raport p5 gagal diimport');
        }

        return redirect()->back()->with('success', 'Data guru raport p5 berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new GuruRaportP5FormatExport(), 'data-guru_raport_p5-mutuharjo' . '.xlsx');
    }
}
