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
        $guru = Guru::where('kode', $row[2])->get()->first();

        $guru->user->update([
            'role' => 'wali kelas',
        ]);

        return new WaliKelas([
            "tahun_pelajaran" => $row[0],
            "kelas" => $row[1],
            "guru_id" => $guru->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
