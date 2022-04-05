<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        $siswa = Siswa::all();

        return view('admin.index', compact('guru', 'siswa'));
    }
}
