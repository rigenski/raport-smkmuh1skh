<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class GuruRaportP5FormatExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        return view('admin/guru-raport-p5/format-table');
    }
}
