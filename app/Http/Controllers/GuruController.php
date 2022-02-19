<?php

namespace App\Http\Controllers;

use App\Exports\GuruFormatExport;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all();

        return view('admin.guru.index', compact('guru'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_guru' => 'required',
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.guru')->with('error', 'Data guru gagal diperbarui');
        }

        $guru = Guru::find($id);

        $guru->update([
            'kode_guru' => $request->kode_guru,
            'nama' => $request->nama
        ]);

        if ($request->password) {
            User::find($guru->user_id)->update([
                "username" => $request->kode_guru,
                "password" => bcrypt($request->password)
            ]);
        }


        User::find($guru->user_id)->update([
            "username" => $request->kode_guru,
        ]);

        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        $guru = Guru::find($id);

        $guru->delete();

        User::find($guru->user_id)->delete();

        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\GuruImport, request()->file('data_guru'));
        } catch (\Exception $ex) {
            return redirect()->route('admin.guru')->with('error', 'Data guru gagal diimport');
        }

        return redirect()->route('admin.guru')->with('success', 'Data guru berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new GuruFormatExport(), 'data-guru-mutuharjo' . '.xlsx');
    }
}
