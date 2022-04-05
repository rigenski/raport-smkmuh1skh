<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Riwayat;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayat = Riwayat::all();

        $kelas = Siswa::all()->unique('kelas')->values()->all();

        $nilai = Nilai::all();

        $semester = [1, 2, 3, 4, 5, 6];

        return view('admin.riwayat.index', compact('riwayat', 'kelas', 'nilai', 'semester'));
    }
}
