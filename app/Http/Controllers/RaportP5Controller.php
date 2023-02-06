<?php

namespace App\Http\Controllers;

use App\Exports\RaportP5FormatExport;
use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\RaportP5;
use App\Models\GuruRaportP5;
use App\Models\RaportP5Projek;
use App\Models\RaportP5Dimensi;
use App\Models\RaportP5Elemen;
use Illuminate\Http\Request;
use App\Models\WaliKelas;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RaportP5Controller extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {
            if (auth()->user()->role == 'admin') { 
                if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                    session(['raport_p5-tahun_pelajaran' => $filter->tahun_pelajaran]);
                    session(['raport_p5-kelas' => $filter->kelas]);
                    session(['raport_p5-semester' => $filter->semester]);

                    $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
                } else {
                    $data_siswa_aktif = [];
                }

                $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

                if($raport_p5) {
                    $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
                } else {
                    $data_raport_p5_projek = [];
                }

                $data_siswa = SiswaAktif::all();

                $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                $data_semester = [1, 2];

                return view('admin.raport-p5.index', compact('filter', 'setting', 'data_angkatan', 'data_semester',  'data_siswa_aktif', 'data_siswa', 'data_raport_p5_projek'));
            } else {
                if ($filter->has('kelas') && $filter->has('semester')) {
                    session(['raport_p5-tahun_pelajaran' => $setting->tahun_pelajaran]);
                    session(['raport_p5-kelas' => $filter->kelas]);
                    session(['raport_p5-semester' => $filter->semester]);

                    $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
                } else {
                    $data_siswa_aktif = [];
                }

                $raport_p5 = RaportP5::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('semester', $request->semester)->first();

                if($raport_p5) {
                    $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
                } else {
                    $data_raport_p5_projek = [];
                }

                $data_siswa = SiswaAktif::all();

                $data_kelas = DB::table('guru_raport_p5')
                    ->where('guru_raport_p5.guru_id', '=', auth()->user()->guru->id)
                    ->get();

                $data_semester = [1, 2];

                return view('admin.raport-p5.index', compact('filter', 'setting', 'data_semester', 'data_siswa_aktif', 'data_kelas', 'data_raport_p5_projek'));
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function setting(Request $request)
    {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();
        $data_semester = [1, 2];

        return view('admin.raport-p5.setting', compact('request', 'raport_p5', 'data_semester'));
    }

    public function editSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun_pelajaran' => 'required',
            'judul' => 'required',
            'catatan' => 'required',
            'semester' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data Raport P5 gagal disimpan');
        }

        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();
        

        if($raport_p5) {
            $raport_p5->update([
                'judul' => $request->judul,
                'catatan' => $request->catatan,
            ]);
        } else {
            RaportP5::create([
                'tahun_pelajaran' => $request->tahun_pelajaran,
                'judul' => $request->judul,
                'catatan' => $request->catatan,
                'semester' => $request->semester,
            ]);
        }


        return redirect()->back()->with('success', 'Data Raport P5 berhasil disimpan');
    }

    public function projek(Request $request)
    {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

        if(!$raport_p5) {
            return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
        }

        $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
        
        return view('admin.raport-p5.projek', compact('request', 'data_raport_p5_projek'));
    }

    public function editProjek(Request $request)
    {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

        if(!$raport_p5) {
            return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
        }

        $data_raport_p5_project_selected = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->whereNotIn('id', $request->id ?? [])->get();

        foreach($data_raport_p5_project_selected as $raport_p5_project) {
            $raport_p5_project->delete();
        }
        
        if($request->nama) {
            foreach ($request->nama as $index => $nama) {
                $raport_p5_project_selected = RaportP5Projek::find((int)$request->id[$index]);
                
                if($raport_p5_project_selected) {
                    $raport_p5_project_selected->update([
                        'nama' => $nama,
                        'deskripsi' => $request->deskripsi[$index]
                    ]);
                } else {
                    RaportP5Projek::create([
                        'nama' => $nama,
                        'deskripsi' => $request->deskripsi[$index],
                        'raport_p5_id' => $raport_p5->id
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data Raport P5 Projek berhasil disimpan');
    }

    public function dimensi(Request $request)
    {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

        if(!$raport_p5) {
            return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
        }

        $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
        $data_raport_p5_dimensi = RaportP5Dimensi::where('raport_p5_projek_id', $request->projek_id)->get();

        return view('admin.raport-p5.dimensi', compact('data_raport_p5_projek', 'data_raport_p5_dimensi', 'request'));
    }

    public function editDimensi(Request $request)
    {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

        if(!$raport_p5) {
            return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
        }

        $data_raport_p5_dimensi_selected = RaportP5Dimensi::where('raport_p5_projek_id', $request->projek_id)->whereNotIn('id', $request->id ?? [])->get();

        foreach($data_raport_p5_dimensi_selected as $raport_p5_dimensi) {
            $raport_p5_dimensi->delete();
        }
        
        if($request->nama) {
            foreach ($request->nama as $index => $nama) {
                $raport_p5_dimensi_selected = RaportP5Dimensi::find((int)$request->id[$index]);
                
                if($raport_p5_dimensi_selected) {
                    $raport_p5_dimensi_selected->update([
                        'nama' => $nama,
                    ]);
                } else {
                    RaportP5Dimensi::create([
                        'nama' => $nama,
                        'raport_p5_projek_id' => $request->projek_id
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data Raport P5 Dimensi berhasil disimpan');
    }

    public function elemen(Request $request)
    {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

        if(!$raport_p5) {
            return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
        }

        $data_raport_p5_projek = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();
        $data_raport_p5_dimensi = RaportP5Dimensi::where('raport_p5_projek_id', $request->projek_id)->get();
        $data_raport_p5_elemen = RaportP5Elemen::where('raport_p5_dimensi_id', $request->dimensi_id)->get();

        return view('admin.raport-p5.elemen', compact('data_raport_p5_projek', 'data_raport_p5_dimensi', 'data_raport_p5_elemen', 'request'));
    }

    public function editElemen(Request $request) {
        $raport_p5 = RaportP5::where('tahun_pelajaran', $request->tahun_pelajaran)->where('semester', $request->semester)->first();

        if(!$raport_p5) {
            return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
        }

        $data_raport_p5_elemen_selected = RaportP5Elemen::where('raport_p5_dimensi_id', $request->dimensi_id)->whereNotIn('id', $request->id ?? [])->get();

        foreach($data_raport_p5_elemen_selected as $raport_p5_elemen) {
            $raport_p5_elemen->delete();
        }
        
        if($request->sub_elemen) {
            foreach ($request->sub_elemen as $index => $sub_elemen) {
                $raport_p5_elemen_selected = RaportP5Elemen::find((int)$request->id[$index]);
                
                if($raport_p5_elemen_selected) {
                    $raport_p5_elemen_selected->update([
                        'sub_elemen' => $sub_elemen,
                        'akhir_fase' => $request->akhir_fase[$index],
                    ]);
                } else {
                    RaportP5Elemen::create([
                        'sub_elemen' => $sub_elemen,
                        'akhir_fase' => $request->akhir_fase[$index],
                        'raport_p5_dimensi_id' => $request->dimensi_id
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data Raport P5 Elemen berhasil disimpan');
    }

    public function print(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse($request->tanggal_raport ? $request->tanggal_raport : now()->toDateString())->translatedFormat('d F Y');

        $setting = Setting::all()->first();
        
        if ($setting) {
            $session_tahun_pelajaran = session()->get('raport_p5-tahun_pelajaran');
            $session_kelas = session()->get('raport_p5-kelas');
            $session_semester = session()->get('raport_p5-semester');

            $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

            $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get()->first();

            $raport_p5 = RaportP5::where('tahun_pelajaran', $session_tahun_pelajaran)->where('semester', $session_semester)->first();

            if(!$raport_p5) {
                return redirect()->route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester])->with('error', 'Isi data Raport P5 terlebih dahulu');
            }
    
            $raport_p5_projek_data = RaportP5Projek::where('raport_p5_id', $raport_p5->id)->get();

            if ($wali_kelas) {
                $mpdf = new Mpdf();
  
                foreach ($data_siswa_aktif as $siswa_aktif) {

                    $raport_p5_projek_header = '';

                    foreach ($raport_p5_projek_data as $index => $raport_p5_projek)  {
                        $raport_p5_projek_header .= "
                        <div style='margin-bottom: 8px;'>
                            <h4 style='margin: 0;font-size: 12px;'>Projek Profil " . ($index + 1) . " | " . $raport_p5_projek->nama . "</h4>
                            <p style='margin: 0;font-size: 12px;color: #555;'>" . $raport_p5_projek->deskripsi . "</p>
                        </div>";
                    }
        
                    $raport_p5_projek_table = '';
        
                    foreach ($raport_p5_projek_data as $index => $raport_p5_projek)  {
                        $raport_p5_dimensi_table = '';
                    
                        foreach($raport_p5_projek->raport_p5_dimensi as $raport_p5_dimensi) {
                            $raport_p5_elemen_table = '';
        
                            foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen) {
                                $nilai = count($raport_p5_elemen->nilai_p5->where('siswa_aktif_id', $siswa_aktif->id)->where('semester', $session_semester)) ? $raport_p5_elemen->nilai_p5->where('siswa_aktif_id', $siswa_aktif->id)->where('semester', $session_semester)->first()->nilai : '-';
                                $icon = "<img src='https://i.ibb.co/W6w8Z89/check.png' style='height: 12px;' />";

                                $raport_p5_elemen_table .= "
                                    <tr>
                                        <td style='width: 20px;vertical-align: top;border-bottom: 1px solid #aaa;padding: 4px 0 4px 8px;'>
                                            <ul>
                                                <li>
                                                </li>
                                            </ul>
                                        </td>
                                        <td style='padding: 4px;border-bottom: 1px solid #aaa;'>
                                            <b>" . $raport_p5_elemen->sub_elemen . ".</b> " . $raport_p5_elemen->akhir_fase . "
                                        </td>
                                        <td style='padding: 4px;border-bottom: 1px solid #aaa;text-align: center;'>" . ($nilai === 'A' ? $icon : null)  . "</td>
                                        <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;text-align: center;'>" . ($nilai === 'B' ? $icon : null) . "</td>
                                        <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;text-align: center;'>" . ($nilai === 'C' ? $icon : null) . "</td>
                                        <td style='padding: 4px;border-bottom: 1px solid #aaa;text-align: center;'>" . ($nilai === 'D' ? $icon : null) . "</td>
                                    </tr>
                                ";
                            }
                            
        
                            $raport_p5_dimensi_table .= "
                            <tr>
                            <th colspan='6' style='text-align: left;background: #eee;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;padding: 4px 8px;'>" . $raport_p5_dimensi->nama . "</th>
                            </tr>
                            " . $raport_p5_elemen_table;      
                        }
                                 
        
                        $raport_p5_projek_table .= "
                        <tr>
                            <th colspan='2' style='font-size: 12px;font-weight: bold;text-align: left;padding: 4px 8px;'>
                                " . ($index + 1) . ". " . $raport_p5_projek->nama . "
                            </th>
                            <th style='width: 56px;font-size: 12px;font-weight: bold;vertical-align: bottom;padding: 4px 8px;'>
                                MB
                            </th>
                            <th style='width: 56px;font-size: 12px;font-weight: bold;vertical-align: bottom;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;padding: 4px 8px;'>
                                SB
                            </th>
                            <th style='width: 56px;font-size: 12px;font-weight: bold;vertical-align: bottom;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;padding: 4px 8px;'>
                                BSH 
                            </th>
                            <th style='width: 56px;font-size: 12px;font-weight: bold;vertical-align: bottom;padding: 4px 8px;'>
                                SAB
                            </th>
                        </tr>
                        " . $raport_p5_dimensi_table;
                    }
        
                    $html = "
                        <html>
                        <head>
                        </head>
                        <body style='font-family: Arial;'>
                            <div style='padding-top: 130px'></div>
                            " . $raport_p5_projek_header . "
            
                            <table style='border-collapse:collapse;border-spacing: 0;width: 100%;font-size: 12px;'>
                                <tbody>
                                " . $raport_p5_projek_table . "
                                    <tr>
                                        <td colspan='6' style='text-align: left;padding: 8px;'>
                                            <span style='font-weight: bold;'>
                                            Catatan proses :
                                            </span>
                                            <br />
                                            <span>
                                        " . $raport_p5->catatan . "
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </body>
                        </html>
                    ";
        
                    $mpdf->WriteHTML($html);
                    $mpdf->Image(asset('https://i.ibb.co/pKZdBfM/logo-sekolah.png'), 184, 13, 'auto', 14, 'png', '', true, false);
        
                    // TOP
                    $mpdf->SetFont('Arial', '', 16);
                    $mpdf->SetXY(16, 12);
                    $mpdf->MultiCell(100, 8, $raport_p5->judul, 0);
        
                    // BIODATA
                    // NAMA
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(16, 32);
                    $mpdf->WriteCell(6.4, 0.4, 'Nama Peserta Didik', 0, 'C');
                    $mpdf->SetXY(52, 32);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(55, 32);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->siswa->nama, 0, 'C');
                    // NISN
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(16, 36);
                    $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
                    $mpdf->SetXY(52, 36);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(55, 36);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->siswa->nis, 0, 'C');
                    // SEKOLAH
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(16, 40);
                    $mpdf->WriteCell(6.4, 0.4, 'Sekolah', 0, 'C');
                    $mpdf->SetXY(52, 40);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(55, 40);
                    $mpdf->WriteCell(6.4, 0.4, $setting->sekolah, 0, 'C');
                    // ALAMAT
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(16, 44);
                    $mpdf->WriteCell(6.4, 0.4, 'Alamat', 0, 'C');
                    $mpdf->SetXY(52, 44);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(55, 44);
                    $mpdf->MultiCell(60, 0.4, $setting->alamat, 0, 'L');
                    // KELAS
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(120, 32);
                    $mpdf->WriteCell(6.4, 0.4, 'Kelas', 0, 'C');
                    $mpdf->SetXY(160, 32);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(162, 32);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->kelas, 0, 'C');
                    // FASE
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(120, 36);
                    $mpdf->WriteCell(6.4, 0.4, 'Fase', 0, 'C');
                    $mpdf->SetXY(160, 36);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(162, 36);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->angkatan == 'X' ? 'E' : 'F', 0, 'C');
                    // SEMESTER
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(120, 40);
                    $mpdf->WriteCell(6.4, 0.4, 'Semester', 0, 'C');
                    $mpdf->SetXY(160, 40);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(162, 40);
                    $mpdf->WriteCell(6.4, 0.4, $session_semester, 0, 'C');            
                    // TAHUN PELAJARAN
                    $mpdf->SetFont('Arial', 'B', 8.6);
                    $mpdf->SetXY(120, 44);
                    $mpdf->WriteCell(6.4, 0.4, 'Tahun Pelajaran', 0, 'C');
                    $mpdf->SetXY(160, 44);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8.6);
                    $mpdf->SetXY(162, 44);
                    $mpdf->WriteCell(6.4, 0.4, $session_tahun_pelajaran, 0, 'C');   
                    // TTD WALI SISWA
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(20, 256);
                    $mpdf->WriteCell(6.4, 0.4, 'Orang Tua / Wali Siswa', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(20, 267);
                    $mpdf->WriteCell(6.4, 0.4, '............................', 0, 'C');

                    // TTD WALI KELAS
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(140, 253);
                    $mpdf->WriteCell(6.4, 0.4, 'Sukoharjo, ' . $date_now, 0, 'C');
                    $mpdf->SetXY(140, 256);
                    $mpdf->WriteCell(6.4, 0.4, 'Wali Kelas', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(140, 267);
                    $mpdf->WriteCell(6.4, 0.4, $wali_kelas->guru->nama, 0, 'C');
                    $mpdf->SetXY(140, 270);
                    $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');
                    // TTD KEPALA SEKOLAH
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 260);
                    $mpdf->WriteCell(6.4, 0.4, 'Mengetahui,', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 263);
                    $mpdf->WriteCell(6.4, 0.4, 'Kepala Sekolah', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 277);
                    $mpdf->WriteCell(6.4, 0.4, $setting->kepala_sekolah, 0, 'C');
                    $mpdf->SetXY(82, 280);
                    $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');         
                }

                $mpdf->Output('Raport P5 Siswa SMK Muhammadiyah 1 Sukoharjo.pdf', 'I');
                exit;
                
                return redirect()->route('admin.raport')->with('success', 'Cetak Raport P5 telah berhasil ...');
            } else {
                return redirect()->back()->with('error', 'Data wali kelas tidak ada');
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\NilaiP5Import(), request()->file('data_raport_p5'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data raport p5 gagal diimport');
        }

        return redirect()->back()->with('success', 'Data raport p5 berhasil diimport');
    }

    public function export_format(Request $request) {
        $guru_raport_p5 = GuruRaportP5::find($request->guru_raport_p5);

        if(!$guru_raport_p5) {
            return redirect()->back()->with('error', 'Data kelas tidak ada ');
        }

        return Excel::download(new RaportP5FormatExport($request->guru_raport_p5, $request->semester), 'Data Nilai P5 '  . $guru_raport_p5->kelas . '.xlsx');
    }
}
