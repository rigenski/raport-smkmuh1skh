<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class GuruFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/guru/format-table');
    }
}
