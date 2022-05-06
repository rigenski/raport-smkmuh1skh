<?php

namespace App\Http\Controllers;

use App\Models\GuruMataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;


        if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
            session(['ranking-tahun_pelajaran' => $filter->tahun_pelajaran]);
            session(['ranking-kelas' => $filter->kelas]);
            session(['ranking-semester' => $filter->semester]);

            $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
        } else {
            $siswa_aktif = [];
        }

        $siswa = SiswaAktif::all();

        $angkatan = ['X', 'XI', 'XII'];

        $semester = [1, 2];

        return view('admin.ranking.index', compact('filter', 'angkatan', 'semester',  'siswa_aktif', 'siswa'));
    }

    public function print()
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse(now());

        $session_tahun_pelajaran = session()->get('ranking-tahun_pelajaran');
        $session_kelas = session()->get('ranking-kelas');
        $session_semester = session()->get('ranking-semester');

        $guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

        $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

        $setting = Setting::all()[0];

        // TABLE

        $table_siswa = '';
        $table_mapel = '';
        $table_kkm = '';
        $list_kejuruan = '';

        $no = 1;

        foreach ($guru_mata_pelajaran as $data) {
            $table_mapel .= "<th style='padding: 4px 4px;border: 1px solid black;'>" . $data->mata_pelajaran->kode_mata_pelajaran . "</th>";
            $table_kkm .= "<td style='padding: 4px 4px;border: 1px solid black;text-align: center;'>" . 75 . "</td>";
        }

        $data_ranking = [];

        $i = 0;
        foreach ($siswa_aktif as $data) {
            $jmlh_nilai = 0;

            foreach ($data->nilai->where('semester', $session_semester) as $nilai) {
                $jmlh_nilai += $nilai->nilai;
            }

            array_push($data_ranking, [$jmlh_nilai, $data->siswa->nomer_induk_siswa]);

            $no++;
            $i++;
        }

        rsort($data_ranking);

        $i = 0;
        foreach ($siswa_aktif as $data) {
            $table_nilai = '';
            $jmlh_nilai = 0;

            foreach ($guru_mata_pelajaran as $data_mata_pelajaran) {
                $nilai = 0;

                foreach ($data->nilai->where('semester', $session_semester)->where('mata_pelajaran_id', $data_mata_pelajaran->mata_pelajaran->id) as $data_nilai) {
                    $nilai = $data_nilai->nilai;
                }

                if ($nilai < 75) {
                    $table_nilai .= "<td style='text-align: center;background-color: #969696;'>" . $nilai . "</td>";
                } else {
                    $table_nilai .= "<td style='text-align: center;'>" . $nilai . "</td>";
                }

                $jmlh_nilai += $nilai;
            }


            $rata_nilai = $jmlh_nilai / count($guru_mata_pelajaran);

            $hasil_ranking = 0;

            foreach ($data_ranking as $index => $ranking) {
                if ($ranking[1] == $data->siswa->nomer_induk_siswa) {
                    $hasil_ranking = $index + 1;
                }
            }

            $table_siswa .= "<tr style='border: none;font-family: Arial;'>
            <td style='text-align: center;padding: 2px 4px;'>" . $no . "</td>
            <td style='text-align: center;padding: 2px 4px;'>" . $data->siswa->nomer_induk_siswa . "</td>
            <td style='text-align: left;padding: 2px 4px;'>" . $data->siswa->nama_siswa . "</td>" . $table_nilai . "<td style='text-align: center;padding: 2px 4px;'>" . $jmlh_nilai  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . $rata_nilai  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . $hasil_ranking  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . '-|-|-'  . "</td>"
                . "</tr>";

            $no++;
            $i++;
        }

        foreach ($guru_mata_pelajaran as $data) {
            if ($data->mata_pelajaran->jenis_mata_pelajaran == 'KELOMPOK KEJURUAN') {
                $list_kejuruan .= "<li style='margin: none;'>" . $data->mata_pelajaran->kode_mata_pelajaran . ' : ' . $data->mata_pelajaran->nama_mata_pelajaran . "</li>";
            };
        }

        // HTML

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
                    <ul style='margin-top: 4px;margin-bottom: 0;list-style: none;padding: none;font-size: 8px;'>
                       " . $list_kejuruan . "
                    </ul>
                    <ul style='margin-top: 2px;list-style: none;padding: none;font-size: 8px;'>
                        <li style='margin: none;padding-top: 2px;'><table style='font-size: 8px;border: none;'><tr><td style='padding-left: 8px;padding-right: 8px;'>Ket: </td><td style='background: #969696;width: 24px;'></td><td style='padding-left: 4px;'>Belum Tuntas</td></tr></table></li>
                        <li style='margin: none;padding-top: 2px;'>Cetak: " . $date_now->translatedFormat('d F Y') . ', ' . $date_now->toTimeString() . ' WIB' . " </li>
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
        $mpdf->WriteCell(6.4, 0.4, strtoupper('DAFTAR NILAI RAPORT SEMESTER ' . ($session_semester  == 1 ? 'GASAL ' : 'GENAP ') . $session_tahun_pelajaran), 0, 'C');
        $mpdf->SetFont('', 'B', 11);
        $mpdf->SetXY(140, 14);
        $mpdf->WriteCell(6.4, 0.4, 'Kelas: ' . $session_kelas . ', Wali Kelas: ' . $wali_kelas->guru->nama_guru, 0, 'C');
        $mpdf->SetFont('Arial', '', 8);
        $mpdf->SetXY(24, 14);
        $mpdf->WriteCell(6.4, 0.4, $setting->alamat, 0, 'C');


        $mpdf->Output('Daftar Nilai Ranking SMK Muhammadiyah 1 Sukoharjo' . '.pdf', 'I');
        exit;
    }
}
