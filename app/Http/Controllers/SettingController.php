<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::all();

        return view('admin.setting.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::all()->first();

        if ($setting) {

            $setting->update([
                'sekolah' => $request->nama_sekolah,
                'kepala_sekolah' => $request->nama_kepala_sekolah,
                'alamat' => $request->alamat,
                'npsn' => $request->npsn,
                'tahun_pelajaran' => $request->tahun_pelajaran,
            ]);

            if ($request->hasFile('logo')) {
                $rand = Str::random(20);
                $name_file = $rand . "." . $request->logo->getClientOriginalExtension();
                $request->file('logo')->move('images/setting', $name_file);
                $setting->logo = $name_file;
                $setting->save();
            }
        } else {
            $setting = Setting::create([
                'sekolah' => $request->nama_sekolah,
                'kepala_sekolah' => $request->nama_kepala_sekolah,
                'alamat' => $request->alamat,
                'npsn' => $request->npsn,
                'tahun_pelajaran' => $request->tahun_pelajaran,
            ]);

            if ($request->hasFile('logo')) {
                $rand = Str::random(20);
                $name_file = $rand . "." . $request->logo->getClientOriginalExtension();
                $request->file('logo')->move('images/setting', $name_file);
                $setting->logo = $name_file;
                $setting->save();
            }
        }

        return redirect()->back()->with('success', 'Data setting sekolah berhasil diperbarui');
    }
}
