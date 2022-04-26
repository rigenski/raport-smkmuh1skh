<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class GuruMataPelajaranFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/guru-mata-pelajaran/format-table');
    }
}
