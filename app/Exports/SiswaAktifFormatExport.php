<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class SiswaAktifFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/siswa_aktif/format-table');
    }
}
