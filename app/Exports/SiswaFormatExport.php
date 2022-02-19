<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class SiswaFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/siswa/format-table');
    }
}
