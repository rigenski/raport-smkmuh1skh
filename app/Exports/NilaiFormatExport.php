<?php

namespace App\Exports;

use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Setting;
use App\Models\SiswaAktif;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class NilaiFormatExport implements FromView, ShouldAutoSize
{

    protected $guru_mata_pelajaran_id;

    function __construct($guru_mata_pelajaran)
    {
        $this->guru_mata_pelajaran_id = $guru_mata_pelajaran;
    }

    public function view(): View
    {
        $guru_mata_pelajaran = GuruMataPelajaran::find($this->guru_mata_pelajaran_id);

        $setting = Setting::all()->first();

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $guru_mata_pelajaran->kelas)->get();
        $mata_pelajaran = $guru_mata_pelajaran->mata_pelajaran;

        return view('admin/nilai/format-table', compact('setting', 'siswa_aktif', 'mata_pelajaran'));
    }
}
