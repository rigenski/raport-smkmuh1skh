<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\WaliKelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class WaliKelasImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $guru = Guru::where('kode_guru', $row[1])->get();

        $guru[0]->user->update([
            'role' => 'wali kelas',
        ]);

        return new WaliKelas([
            "kelas" => $row[0],
            "guru_id" => $guru[0]->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
