<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GuruImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $user = User::create([
            "role" => "guru",
            "username" => $row[1],
            "password" => bcrypt($row[2])
        ]);

        return new Guru([
            "kode_guru" => $row[1],
            "nama" => $row[0],
            "user_id" => $user->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
