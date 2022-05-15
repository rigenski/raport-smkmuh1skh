<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if ($filter->has('tahun_pelajaran')) {
                $data_dokumen = Dokumen::where('tahun_pelajaran', $filter->tahun_pelajaran)->orderBy('created_at', 'DESC')->get();
            } else {
                $data_dokumen = Dokumen::where('tahun_pelajaran', $setting->tahun_pelajaran)->orderBy('created_at', 'DESC')->get();
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }

        return view('admin.dokumen.index', compact('filter', 'setting', 'data_dokumen'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_pelajaran' => 'required',
            'nama' => 'required',
            'dokumen' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data dokumen gagal ditambahkan');
        }

        $dokumen = Dokumen::create([
            'tahun_pelajaran' => $request->tahun_pelajaran,
            'nama' => $request->nama,
        ]);

        if ($request->hasFile('dokumen')) {
            $rand = Str::random(10);
            $name_file = pathinfo($request->dokumen->getClientOriginalName(), PATHINFO_FILENAME) . ' - ' . $rand . "." . $request->dokumen->getClientOriginalExtension();
            $request->file('dokumen')->move('dokumen', $name_file);
            $dokumen->dokumen = $name_file;
            $dokumen->save();
        }

        return redirect()->back()->with('success', 'Data dokumen berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data dokumen gagal diperbarui');
        }

        $dokumen = Dokumen::find($id);

        $dokumen->update([
            'nama' => $request->nama,
        ]);

        if ($request->hasFile('dokumen')) {
            $rand = Str::random(10);
            $name_file = pathinfo($request->dokumen->getClientOriginalName(), PATHINFO_FILENAME) . '-' . $rand . "." . $request->dokumen->getClientOriginalExtension();
            $request->file('dokumen')->move('dokumen', $name_file);
            $dokumen->dokumen = $name_file;
            $dokumen->save();
        }

        return redirect()->back()->with('success', 'Data dokumen berhasil diperbarui');
    }

    public function destroy($id)
    {
        $dokumen = Dokumen::find($id);

        $dokumen->delete();

        return redirect()->back()->with('success', 'Data dokumen berhasil dihapus');
    }
}
