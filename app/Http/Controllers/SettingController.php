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

        if (count($setting)) {
            $is_setting = true;

            $setting = $setting[0];
        } else {
            $is_setting = false;

            $setting = [];
        }

        return view('admin.setting.index', compact('setting', 'is_setting'));
    }

    public function update(Request $request)
    {
        $data_setting = Setting::all();

        if (count($data_setting)) {
            $setting = $data_setting[0];

            $setting->update([
                'sekolah' => $request->nama_sekolah,
                'kepala_sekolah' => $request->nama_kepala_sekolah,
                'alamat' => $request->alamat,
                'npsn' => $request->npsn,
                'tahun_pelajaran' => $request->tahun_pelajaran,
            ]);

            if ($request->hasFile('logo')) {
                $rand = Str::random(20);
                $name_image = $rand . "." . $request->logo->getClientOriginalExtension();
                $request->file('logo')->move('images/setting', $name_image);
                $setting->logo = $name_image;
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
                $name_image = $rand . "." . $request->logo->getClientOriginalExtension();
                $request->file('logo')->move('images/setting', $name_image);
                $setting->logo = $name_image;
                $setting->save();
            }
        }

        return redirect()->back()->with('success', 'Data setting sekolah berhasil diperbarui');
    }
}
