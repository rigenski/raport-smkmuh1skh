<?php

namespace App\Exports;

use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\RaportP5Dimensi;


class RaportP5FormatExport implements FromView, ShouldAutoSize
{

    protected $wali_kelas_id;

    function __construct($wali_kelas)
    {
        $this->wali_kelas_id = $wali_kelas;
    }

    public function view(): View
    {
        $wali_kelas = WaliKelas::find($this->wali_kelas_id);

        $setting = Setting::all()->first();

        $data_raport_p5_dimensi = RaportP5Dimensi::all();

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $wali_kelas->kelas)->get();

        return view('admin/raport-p5/format-table', compact('setting', 'siswa_aktif', 'data_raport_p5_dimensi'));
    }
}
