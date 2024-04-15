<?php

namespace App\Exports;

use App\Models\Setting;
use App\Models\SiswaAktif;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class NilaiIjazahFormatExport implements FromView, ShouldAutoSize
{
    protected $kelas;
    protected $mata_pelajaran;

    function __construct($kelas, $mata_pelajaran)
    {
        $this->kelas = $kelas;
        $this->mata_pelajaran = $mata_pelajaran;
    }

    public function view(): View
    {
        $setting = Setting::all()->first();

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $this->kelas)->get();
        $mata_pelajaran = $this->mata_pelajaran;

        return view('admin/nilai-ijazah/format-table', compact('setting', 'siswa_aktif', 'mata_pelajaran'));
    }
}
