<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\RaportP5;
use App\Models\RaportP5Projek;
use App\Models\RaportP5Dimensi;
use App\Models\RaportP5Elemen;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class RaportP5Controller extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        return view('admin.raport-p5.index', compact('filter'));
    }

    public function projek()
    {
        $data_raport_p5_projek = RaportP5Projek::all();
        
        return view('admin.raport-p5.projek', compact('data_raport_p5_projek'));
    }

    public function editProjek(Request $request)
    {
        $raport_p5 = RaportP5::all()->first();

        if(!$raport_p5) {
            $raport_p5 = RaportP5::create([
                'judul' => 'RAPORT PENGUATAN PROFIL PELAJAR PANCASILA'
            ]);
        }

        $data_raport_p5_project_selected = RaportP5Projek::whereNotIn('id', $request->id ?? [])->get();

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
        $data_raport_p5_projek = RaportP5Projek::all();
        $data_raport_p5_dimensi = RaportP5Dimensi::where('raport_p5_projek_id', $request->projek_id)->get();

        return view('admin.raport-p5.dimensi', compact('data_raport_p5_projek', 'data_raport_p5_dimensi', 'request'));
    }

    public function editDimensi(Request $request)
    {
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
        $data_raport_p5_projek = RaportP5Projek::all();
        $data_raport_p5_dimensi = RaportP5Dimensi::where('raport_p5_projek_id', $request->projek_id)->get();
        $data_raport_p5_elemen = RaportP5Elemen::where('raport_p5_dimensi_id', $request->dimensi_id)->get();

        return view('admin.raport-p5.elemen', compact('data_raport_p5_projek', 'data_raport_p5_dimensi', 'data_raport_p5_elemen', 'request'));
    }

    public function editElemen(Request $request) {
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

    public function catatan()
    {
        $raport_p5 = RaportP5::all()->first();

        return view('admin.raport-p5.catatan', compact('raport_p5'));
    }
    
    public function editCatatan(Request $request)
    {
        $raport_p5 = RaportP5::all()->first();

        if($raport_p5) {
            $raport_p5->update([
                'catatan' => $request->catatan
            ]);
        }

        return redirect()->back()->with('success', 'Data Raport P5 Catatan berhasil disimpan');
    }

    public function print()
    {
        $mpdf = new Mpdf();

        $setting = Setting::all()->first();

        if ($setting) {


            $html = "
            <html>
            <head>
            </head>
            <body style='font-family: Arial;'>
                <div style='padding-top: 160px'></div>
                <div style='margin-bottom: 16px;'>
                    <h4 style='margin: 0;'>Projek Profil 1 | Donec sollicitudin molestie malesuada</h4>
                    <p style='margin: 0;color: #555;'>Nulla quis lorem ut libero malesuada feugiat. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Proin eget tortor risus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Nulla porttitor accumsan tincidunt. Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh.</p>
                </div>
                <div style='margin-bottom: 16px;'>
                    <h4 style='margin: 0;'>Projek Profil 2 | Donec sollicitudin molestie malesuada</h4>
                    <p style='margin: 0;color: #555;'>Nulla quis lorem ut libero malesuada feugiat. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Proin eget tortor risus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Nulla porttitor accumsan tincidunt. Curabitur aliquet quam id dui posuere blandit. Sed porttitor lectus nibh.</p>
                </div>

                <table style='border-collapse:collapse;border-spacing: 0;width: 100%;font-size: 14px;'>
                    <tbody>
                        <tr>
                            <th colspan='2' style='font-size: 18px;font-weight: bold;text-align: left;padding: 4px 8px;'>
                                1. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae
                            </th>
                            <th style='width: 56px;;font-weight: bold;vertical-align: bottom;padding: 4px 8px;'>
                                MB
                            </th>
                            <th style='width: 56px;;font-weight: bold;vertical-align: bottom;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;padding: 4px 8px;'>
                                SB
                            </th>
                            <th style='width: 56px;;font-weight: bold;vertical-align: bottom;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;padding: 4px 8px;'>
                                BSH 
                            </th>
                            <th style='width: 56px;;font-weight: bold;vertical-align: bottom;padding: 4px 8px;'>
                                SAB
                            </th>
                        </tr>
                        <tr>
                            <th colspan='6' style='text-align: left;background: #eee;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;padding: 4px 8px;'>Quisque velit nisi</th>
                        </tr>
                        <tr>
                            <td style='vertical-align: top;border-bottom: 1px solid #aaa;padding: 4px 0 4px 8px;'>
                                <ul>
                                    <li>
                                    </li>
                                </ul>
                            </td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'>
                                <b>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</b> Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'></td>
                        </tr>
                        <tr>
                            <td style='vertical-align: top;border-bottom: 1px solid #aaa;padding: 4px 0 4px 8px;'>
                                <ul>
                                    <li>
                                    </li>
                                </ul>
                            </td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'>
                                <b>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</b> Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'></td>
                        </tr>
                        <tr>
                            <th colspan='6' style='text-align: left;background: #eee;border-top: 1px solid #aaa;border-bottom: 1px solid #aaa;padding: 4px 8px;'>Quisque velit nisi</th>
                        </tr>
                        <tr>
                            <td style='vertical-align: top;border-bottom: 1px solid #aaa;padding: 4px 0 4px 8px;'>
                                <ul>
                                    <li>
                                    </li>
                                </ul>
                            </td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'>
                                <b>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae.</b> Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Pellentesque in ipsum id orci porta dapibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;border-left: 1px dotted #aaa;border-right: 1px dotted #aaa;'></td>
                            <td style='padding: 4px;border-bottom: 1px solid #aaa;'></td>
                        </tr>
                        <tr>
                            <td colspan='6' style='text-align: left;padding: 8px;'>
                                <span style='font-weight: bold;'>
                                Catatan proses :
                                </span>
                                <br />
                                <span>
                                Vivamus suscipit tortor eget felis porttitor volutpat. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
            </html>
            ";

            $mpdf->WriteHTML($html);
            $mpdf->Image(asset('/images/setting/' . $setting->logo), 184, 13, 'auto', 14, 'png', '', true, false);

            // TOP
            $mpdf->SetFont('Arial', '', 16);
            $mpdf->SetXY(16, 16);
            $mpdf->WriteCell(6.4, 0.4, 'RAPORT PROJEK PENGUATAN PROFIL', 0, 'C');
            $mpdf->SetXY(16, 24);
            $mpdf->WriteCell(6.4, 0.4, 'PELAJAR PANCASILA', 0, 'C');

            // BIODATA
            // NAMA
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(16, 36);
            $mpdf->WriteCell(6.4, 0.4, 'Nama Peserta Didik', 0, 'C');
            $mpdf->SetXY(52, 36);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(55, 36);
            $mpdf->WriteCell(6.4, 0.4, 'Rigen Maulana', 0, 'C');
            // NISN
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(16, 40);
            $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
            $mpdf->SetXY(52, 40);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(55, 40);
            $mpdf->WriteCell(6.4, 0.4, '11123', 0, 'C');
            // SEKOLAH
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(16, 44);
            $mpdf->WriteCell(6.4, 0.4, 'Sekolah', 0, 'C');
            $mpdf->SetXY(52, 44);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(55, 44);
            $mpdf->WriteCell(6.4, 0.4, 'SMK Muhammadiyah 1 Sukoharjo', 0, 'C');
            // ALAMAT
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(16, 48);
            $mpdf->WriteCell(6.4, 0.4, 'Alamat', 0, 'C');
            $mpdf->SetXY(52, 48);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(55, 48);
            $mpdf->MultiCell(60, 0.4, 'Jl. Anggrek No. 2 Sukoharjo', 0, 'L');
            // KELAS
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(120, 36);
            $mpdf->WriteCell(6.4, 0.4, 'Kelas', 0, 'C');
            $mpdf->SetXY(160, 36);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(162, 36);
            $mpdf->WriteCell(6.4, 0.4, 'X PPLG 1', 0, 'C');
            // FASE
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(120, 40);
            $mpdf->WriteCell(6.4, 0.4, 'Fase', 0, 'C');
            $mpdf->SetXY(160, 40);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(162, 40);
            $mpdf->WriteCell(6.4, 0.4, 'X' == 'X' ? 'E' : 'F', 0, 'C');
            // TAHUN PELAJARAN
            $mpdf->SetFont('Arial', 'B', 8.6);
            $mpdf->SetXY(120, 44);
            $mpdf->WriteCell(6.4, 0.4, 'Tahun Pelajaran', 0, 'C');
            $mpdf->SetXY(160, 44);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 8.6);
            $mpdf->SetXY(162, 44);
            $mpdf->WriteCell(6.4, 0.4, '2021/2022', 0, 'C');

            $mpdf->Output('Raport P5 Siswa SMK Muhammadiyah 1 Sukoharjo.pdf', 'I');
            exit;
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }
}
