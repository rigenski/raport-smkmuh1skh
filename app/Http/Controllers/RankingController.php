<?php

namespace App\Http\Controllers;

use App\Models\GuruMataPelajaran;
use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Exports\RankingExport;
use Maatwebsite\Excel\Facades\Excel;


class RankingController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {

            if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                session(['ranking-tahun_pelajaran' => $filter->tahun_pelajaran]);
                session(['ranking-kelas' => $filter->kelas]);
                session(['ranking-semester' => $filter->semester]);

                $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
            } else {
                $data_siswa_aktif = [];
            }

            $data_siswa = SiswaAktif::all();

            $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

            $data_semester = [1, 2];

            return view('admin.ranking.index', compact('filter', 'setting', 'data_angkatan', 'data_semester',  'data_siswa_aktif', 'data_siswa'));
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function print(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse(now());

        $date_legger = Carbon::parse($request->tanggal_legger ? $request->tanggal_legger : now()->toDateString())->translatedFormat('d F Y');

        $setting = Setting::all()->first();

        if ($setting) {
            $session_tahun_pelajaran = session()->get('ranking-tahun_pelajaran');
            $session_kelas = session()->get('ranking-kelas');
            $session_semester = session()->get('ranking-semester');

            $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

            $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

            $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->first();

            if ($wali_kelas) {
                // TABLE

                $table_siswa = '';
                $table_mapel = '';
                $table_kkm = '';
                $list_kejuruan = '';



                $data_nama_mata_pelajaran = [];

                foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran) {
                    array_push($data_nama_mata_pelajaran, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]);
                }

                sort($data_nama_mata_pelajaran);

                foreach ($data_nama_mata_pelajaran as $nama_mata_pelajaran) {
                    $table_mapel .= "<th style='padding: 4px 4px;border: 1px solid black;width: 34px;'>" . $nama_mata_pelajaran[1] . "</th>";
                    $table_kkm .= "<td style='padding: 4px 4px;border: 1px solid black;text-align: center;width: 34px;'>" . 75 . "</td>";
                }

                $data_ranking = [];

                $i = 0;
                foreach ($data_siswa_aktif as $siswa_aktif) {
                    $jmlh_nilai = 0;

                    foreach ($siswa_aktif->nilai->where('semester', $session_semester) as $nilai) {
                        $jmlh_nilai += (int)$nilai->nilai;
                    }

                    array_push($data_ranking, [$jmlh_nilai, $siswa_aktif->siswa->nis]);

                    $i++;
                }

                rsort($data_ranking);

                $no = 1;
                $i = 0;
                foreach ($data_siswa_aktif as $siswa_aktif) {
                    $table_nilai = '';
                    $jmlh_nilai = 0;

                    $data_total_nilai = [];

                    foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran) {
                        $nilai = 0;

                        foreach ($siswa_aktif->nilai->where('semester', $session_semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai) {
                            $nilai = (int)$data_nilai->nilai;
                        }

                        $jmlh_nilai += $nilai;

                        array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]);
                    }

                    sort($data_total_nilai);

                    foreach ($data_total_nilai as $total_nilai) {
                        if ($total_nilai[1] < 75) {
                            $table_nilai .= "<td style='text-align: center;background-color: #969696;width: 34px;'>" . $total_nilai[1] . "</td>";
                        } else {
                            $table_nilai .= "<td style='text-align: center;width: 34px;'>" . $total_nilai[1] . "</td>";
                        }
                    }


                    $rata_nilai = $jmlh_nilai / count($data_guru_mata_pelajaran);

                    $hasil_ranking = 0;

                    foreach ($data_ranking as $index => $ranking) {
                        if ($ranking[1] == $siswa_aktif->siswa->nis) {
                            $hasil_ranking = $index + 1;
                        }
                    }

                    $ketidakhadiran = $siswa_aktif->ketidakhadiran->where('semester', $session_semester)->first();

                    $table_siswa .= "<tr style='border: none;font-family: Arial;'>
            <td style='text-align: center;padding: 2px 4px;'>" . $no . "</td>
            <td style='text-align: center;padding: 2px 4px;'>" . $siswa_aktif->siswa->nis . "</td>
            <td style='text-align: left;padding: 2px 4px;'>" . $siswa_aktif->siswa->nama . "</td>" . $table_nilai . "<td style='text-align: center;padding: 2px 4px;width: 34px;'>" . $jmlh_nilai  . "</td>" . "<td style='text-align: center;padding: 2px 4px;'>" . substr($rata_nilai, 0, 4)  . "</td>" . "<td style='text-align: center;padding: 2px 4px;width: 34px;'>" . $hasil_ranking  . "</td>" . "<td style='text-align: center;padding: 2px 4px;width: 34px;'>" . ($ketidakhadiran ? $ketidakhadiran->sakit . '|' . $ketidakhadiran->izin . '|' . $ketidakhadiran->tanpa_keterangan : '-|-|-') . "</td>"
                        . "</tr>";

                    $no++;
                    $i++;
                }

                foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran) {
                    if ($guru_mata_pelajaran->mata_pelajaran->jenis == 'KELOMPOK KEJURUAN') {
                        $list_kejuruan .= "<li style='margin: none;'>" . $guru_mata_pelajaran->mata_pelajaran->kode . ' : ' . $guru_mata_pelajaran->mata_pelajaran->nama . "</li>";
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
                                <th style='padding: 4px 4px;border: 1px solid #000;'>NAMA SISWA</th>" . $table_mapel . "
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 34px;'>JMLH</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 34px;'>RATA</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 34px;'>RANK</th>
                                <th style='padding: 4px 4px;border: 1px solid #000;width: 34px;'>S-I-A</th>
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
                        <p style='text-align: right;margin-right: 86px;margin-top: 0;font-size: 12px;'>Sukoharjo, " . $date_legger .  "</p>
                    </body>
                    </html>
                    ";

                $mpdf = new Mpdf();
                $mpdf->AddPage('L', '', '', '', '', 8, 8, 18, 8, 0, 0);
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($html);
                // HEADER 
                $mpdf->Image(asset('/images/setting/' . $setting->logo), 12, 4, 'auto', 12, 'png', '', true, false);
                $mpdf->SetFont('', 'B', 11);
                $mpdf->SetXY(24, 8);
                $mpdf->WriteCell(6.4, 0.4, strtoupper($setting->sekolah), 0, 'C');
                $mpdf->SetFont('', 'B', 11);
                $mpdf->SetXY(140, 8);
                $mpdf->WriteCell(6.4, 0.4, strtoupper('DAFTAR NILAI RAPORT SEMESTER ' . ($session_semester  == 1 ? 'GASAL ' : 'GENAP ') . $session_tahun_pelajaran), 0, 'C');
                $mpdf->SetFont('', 'B', 11);
                $mpdf->SetXY(140, 14);
                $mpdf->WriteCell(6.4, 0.4, 'Kelas: ' . $session_kelas . ', Wali Kelas: ' . $wali_kelas->guru->nama, 0, 'C');
                $mpdf->SetFont('Arial', '', 8);
                $mpdf->SetXY(24, 14);
                $mpdf->WriteCell(6.4, 0.4, $setting->alamat, 0, 'C');


                $mpdf->Output('Simaku - Daftar Nilai' . '.pdf', 'I');
                exit;
            } else {
                return redirect()->back()->with('error', 'Data wali kelas tidak ada');
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function export_excel(Request $request)
    {
        return Excel::download(new RankingExport($request->tanggal_legger), 'Simaku - Daftar Nilai' . '.xlsx');
    }
}
