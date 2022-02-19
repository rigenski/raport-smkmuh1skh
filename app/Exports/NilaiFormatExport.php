<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class NilaiFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/nilai/format-table');
    }
}
