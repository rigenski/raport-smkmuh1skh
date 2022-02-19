<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class KelasFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/kelas/format-table');
    }
}
