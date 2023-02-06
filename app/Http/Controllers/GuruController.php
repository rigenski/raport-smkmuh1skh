<?php

namespace App\Http\Controllers;

use App\Exports\GuruFormatExport;
use App\Models\Guru;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index()
    {
        $setting = Setting::all();

        if (count($setting)) {
            $data_guru = Guru::all();
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }

        return view('admin.guru.index', compact('data_guru'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.guru')->with('error', 'Data guru gagal diperbarui');
        }

        $guru = Guru::find($id);

        $guru->update([
            'kode' => $request->kode,
            'nama' => $request->nama
        ]);

        if ($request->password) {
            User::find($guru->user_id)->update([
                "username" => $request->kode,
                "password" => bcrypt($request->password)
            ]);
        } else {
            User::find($guru->user_id)->update([
                "username" => $request->kode,
            ]);
        }

        return redirect()->back()->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        $guru = Guru::find($id);

        $guru->user->delete();

        return redirect()->back()->with('success', 'Data guru berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\GuruImport, request()->file('data_guru'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data guru gagal diimport');
        }

        return redirect()->back()->with('success', 'Data guru berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new GuruFormatExport(), 'Data Guru' . '.xlsx');
    }
}
