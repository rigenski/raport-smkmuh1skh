<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class MataPelajaranFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/mata-pelajaran/format-table');
    }
}
