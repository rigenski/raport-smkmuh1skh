<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class MapelFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/mapel/format-table');
    }
}
