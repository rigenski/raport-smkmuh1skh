<?php

namespace App\Exports;

use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use App\Models\GuruRaportP5;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\RaportP5;
use App\Models\RaportP5Projek;


class RaportP5FormatExport implements FromView, ShouldAutoSize
{

    protected $guru_raport_p5_id;
    protected $semester;

    function __construct($guru_raport_p5, $_semester)
    {
        $this->guru_raport_p5_id = $guru_raport_p5;
        $this->semester = $_semester;
    }

    public function view(): View
    {
        $guru_raport_p5 = GuruRaportP5::find($this->guru_raport_p5_id);

        $setting = Setting::all()->first();

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $guru_raport_p5->kelas)->get();

        $raport_p5 = RaportP5::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $this->semester)->first();

        if($raport_p5) {
            $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
        } else {
            $data_raport_p5_projek = [];
        }

        $semester = $this->semester;

        return view('admin/raport-p5/format-table', compact('setting', 'siswa_aktif', 'data_raport_p5_projek', 'semester'));
    }
}
