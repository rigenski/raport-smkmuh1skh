<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        if ($filter->tipe == 'angkatan') {
            $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('angkatan', $filter->angkatan)->unique('siswa_id')->values()->all();


            session(['raport-tipe' => $filter->tipe]);
            session(['raport-tahun_pelajaran' => $filter->tahun_pelajaran]);
            session(['raport-angkatan' => $filter->angkatan]);
            session(['raport-semester' => $filter->semester]);
        } else if ($filter->tipe == 'angkatan-jurusan') {
            $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('angkatan', $filter->angkatan)->where('jurusan', $filter->jurusan)->unique('siswa_id')->values()->all();

            session(['raport-tipe' => $filter->tipe]);

            session(['raport-tahun_pelajaran' => $filter->tahun_pelajaran]);
            session(['raport-angkatan' => $filter->angkatan]);
            session(['raport-jurusan' => $filter->jurusan]);
            session(['raport-semester' => $filter->semester]);
        } else if ($filter->tipe == 'kelas') {
            $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('kelas', $filter->kelas)->unique('siswa_id')->values()->all();

            session(['raport-tipe' => $filter->tipe]);
            session(['raport-tahun_pelajaran' => $filter->tahun_pelajaran]);
            session(['raport-kelas' => $filter->kelas]);
            session(['raport-semester' => $filter->semester]);
        } else {
            $siswa = [];
        }

        $nilai = Nilai::all();

        $semester = [1, 2];

        $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

        return view('admin.ranking.index', compact('filter', 'nilai', 'semester',  'siswa', 'tahun_pelajaran'));
    }

    public function print()
    {
        $date_now = Carbon::parse(now());

        $session_tipe = session()->get('raport-tipe');

        if ($session_tipe == 'angkatan') {
            $session_tahun_pelajaran = session()->get('raport-tahun_pelajaran');
            $session_angkatan = session()->get('raport-angkatan');
            $session_semester = session()->get('raport-semester');

            $data_nilai = Nilai::all()->where('tahun_pelajaran', $session_tahun_pelajaran)->where('semester', $session_semester)->where('angkatan', $session_angkatan)->unique('siswa_id')->values()->all();
        } else if ($session_tipe == 'angkatan-jurusan') {
            $session_tahun_pelajaran = session()->get('raport-tahun_pelajaran');
            $session_angkatan = session()->get('raport-angkatan');
            $session_jurusan = session()->get('raport-jurusan');
            $session_semester = session()->get('raport-semester');

            $data_nilai = Nilai::all()->where('tahun_pelajaran', $session_tahun_pelajaran)->where('semester', $session_semester)->where('angkatan', $session_angkatan)->where('jurusan', $session_jurusan)->unique('siswa_id')->values()->all();
        } else if ($session_tipe == 'kelas') {
            $session_tahun_pelajaran = session()->get('raport-tahun_pelajaran');
            $session_kelas = session()->get('raport-kelas');
            $session_semester = session()->get('raport-semester');


            $data_nilai = Nilai::all()->where('tahun_pelajaran', $session_tahun_pelajaran)->where('semester', $session_semester)->where('kelas', $session_kelas)->unique('siswa_id')->values()->all();
            $wali_kelas = WaliKelas::where('kelas', $session_kelas)->get();
        }

        $setting = Setting::all();

        $tahun_pelajaran = $data_nilai[0]->siswa->nilai[0]->tahun_pelajaran;

        if (count($setting)) {
            $setting = $setting[0];

            $table_siswa = '';
            $table_mapel = '';
            $table_kkm = '';

            if ($session_semester) {

                $no = 1;
                foreach ($data_nilai[0]->siswa->nilai as $data) {
                    $table_mapel .= "<th style='padding: 4px 4px;border: 1px solid black;'>" . $data->siswa->nilai[0]->mapel . "</th>";
                    $table_kkm .= "<td style='padding: 4px 4px;border: 1px solid black;text-align: center;'>" . 75 . "</td>";
                }

                $data_ranking = [];

                $i = 0;
                foreach ($data_nilai as $data) {
                    $jmlh_nilai = 0;

                    foreach ($data->siswa->nilai as $nilai) {
                        $jmlh_nilai += $nilai->nilai;
                    }

                    array_push($data_ranking, [$jmlh_nilai, $data->siswa->nis]);

                    $no++;
                    $i++;
                }

                rsort($data_ranking);

                $i = 0;
                foreach ($data_nilai as $data) {

                    $table_nilai = '';
                    $jmlh_nilai = 0;

                    foreach ($data->siswa->nilai as $nilai) {
                        if ($nilai->nilai < 75) {
                            $table_nilai .= "<td style='text-align: center;background-color: #969696;'>" . $nilai->nilai . "</td>";
                        } else {
                            $table_nilai .= "<td style='text-align: center;'>" . $nilai->nilai . "</td>";
                        }

                        $jmlh_nilai += $nilai->nilai;
                    }


                    $rata_nilai = $jmlh_nilai / count($data->siswa->nilai);

                    $hasil_ranking = 0;

                    foreach ($data_ranking as $index => $ranking) {
                        if ($ranking[1] == $data->siswa->nis) {
                            $hasil_ranking = $index + 1;
                        }
                    }

                    $table_siswa .= "<tr style='border: none;font-family: Arial;'>
                <td style='text-align: center;padding: 2px 4px;'>" . $no . "</td>
                <td style='text-align: center;padding: 2px 4px;'>" . $data->siswa->nis . "</td>
                <td style='text-align: left;padding: 2px 4px;'>" . $data->siswa->nama . "</td>" . $table_nilai . "<td style='text-align: center;padding: 2px 4px;'>" . $jmlh_nilai  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . $rata_nilai  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . $hasil_ranking  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . '-|-|-'  . "</td>"
                        . "</tr>";

                    $no++;
                    $i++;
                }
            }

            $html = "
                    <html>
                    <head>
                        <style>
                        table {
                            border-collapse: collapse;
                            border-bottom: 1px solid #000;
                        }
                        </style>
                    </head>
                    <body>
                    <table style='width: 100%;font-size: 8px;'>
                        <thead>
                            <tr style='background-color: #969696;border: 1px solid #000;font-family: Times New Roman;'>
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 10px;'>NO</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 48px;'>NIS</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 256px;'>NAMA SISWA</th>" . $table_mapel . "
                                <th style='padding: 4px 4px;border: 1px solid #000;'>JMLH</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;'>RATA</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;'>RANK</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;'>S-I-A</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style='border: 1px solid #000;font-family: Arial;'>
                                <td colspan='3' style='text-align: right;padding: 2px 4px;'>KKM</td>" . $table_kkm . "
                                <td colspan='4'></td>
                            </tr>
                        " . $table_siswa . "
                        </tbody>
                    </table>
                    <ul style='margin-top: 4px;list-style: none;padding: none;font-size: 8px;display:'>
                        <li style='margin: none;padding-top: 2px;'><table style='font-size: 8px;border: none;'><tr><td>Ket: </td><td style='background: #969696;width: 24px;'></td><td>Belum Tuntas</td></tr></table></li>
                        <li style='margin: none;padding-top: 2px;'>Cetak: " . $date_now->translatedFormat('d F Y') . ', ' . $date_now->toTimeString() . " </li>
                    </ul>
                        <p style='text-align: right;margin-right: 86px;margin-top: 48px;font-size: 12px;'>Sukoharjo, " . $date_now->translatedFormat('d F Y') .  "</p>
                    </body>
                    </html>
                    ";

            $mpdf = new Mpdf();
            $mpdf->AddPage('L', '', '', '', '', 8, 8, 18, 18, 0, 0);
            $mpdf->showImageErrors = true;
            $mpdf->WriteHTML($html);
            // HEADER 
            $mpdf->Image(asset('/images/setting/' . $setting->logo), 12, 2, 'auto', 14, 'png', '', true, false);
            $mpdf->SetFont('', 'B', 11);
            $mpdf->SetXY(24, 8);
            $mpdf->WriteCell(6.4, 0.4, strtoupper($setting->sekolah), 0, 'C');
            $mpdf->SetFont('', 'B', 11);
            $mpdf->SetXY(140, 8);
            $mpdf->WriteCell(6.4, 0.4, strtoupper('DAFTAR NILAI RAPORT SEMESTER GASAL ' . $tahun_pelajaran), 0, 'C');
            $mpdf->SetFont('', 'B', 11);
            $mpdf->SetXY(140, 14);
            if ($session_tipe == 'angkatan') {
                $mpdf->WriteCell(6.4, 0.4, 'Angkatan: ' . strtoupper($session_angkatan), 0, 'C');
            } else if ($session_tipe == 'angkatan-jurusan') {
                $mpdf->WriteCell(6.4, 0.4, 'Angkatan: ' . strtoupper($session_angkatan) . ', Jurusan: ' . $session_jurusan, 0, 'C');
            } else if ($session_tipe == 'kelas') {
                $mpdf->WriteCell(6.4, 0.4, 'Kelas: ' . $session_kelas . ', Wali Kelas: ' . $wali_kelas[0]->guru->nama, 0, 'C');
            }
            $mpdf->SetFont('Arial', '', 8);
            $mpdf->SetXY(24, 14);
            $mpdf->WriteCell(6.4, 0.4, $setting->alamat, 0, 'C');


            $mpdf->Output('Daftar Nilai Ranking SMK Muhammadiyah 1 Sukoharjo' . '.pdf', 'I');
            exit;
        }
    }
}
