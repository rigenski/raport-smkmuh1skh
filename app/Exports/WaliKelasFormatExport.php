<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class WaliKelasFormatExport implements FromView
{

    public function view(): View
    {
        return view('admin/wali-kelas/format-table');
    }
}
