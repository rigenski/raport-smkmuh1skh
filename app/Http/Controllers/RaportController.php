<?php

namespace App\Http\Controllers;

use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class RaportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {
            if (auth()->user()->role == 'admin') {

                if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                    session(['raport-tahun_pelajaran' => $filter->tahun_pelajaran]);
                    session(['raport-kelas' => $filter->kelas]);
                    session(['raport-semester' => $filter->semester]);

                    $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();

                    $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
                } else {
                    $data_siswa_aktif = [];
                    $data_guru_mata_pelajaran = [];
                }

                $data_siswa = SiswaAktif::all();

                $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                $data_semester = [1, 2];

                return view('admin.raport.index', compact('filter', 'setting', 'data_angkatan', 'data_semester',  'data_siswa_aktif', 'data_siswa', 'data_guru_mata_pelajaran'));
            } else {

                $wali_kelas = WaliKelas::where('guru_id', auth()->user()->guru->id)->where('tahun_pelajaran', $setting->tahun_pelajaran)->get()->first();

                $semester = 1;

                if ($wali_kelas) {
                    $kelas = $wali_kelas->kelas;

                    session(['raport-tahun_pelajaran' => $setting->tahun_pelajaran]);
                    session(['raport-kelas' => $kelas]);
                    session(['raport-semester' => $filter->semester ? $filter->semester : '1']);

                    session(['ranking-tahun_pelajaran' => $setting->tahun_pelajaran]);
                    session(['ranking-kelas' => $kelas]);
                    session(['ranking-semester' => $filter->semester ? $filter->semester : '1']);

                    if ($filter->has('semester')) {

                        $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $kelas)->get();

                        $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $kelas)->get();
                    } else {
                        $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $kelas)->get();

                        $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $kelas)->get();
                    }

                    $data_siswa = SiswaAktif::all();

                    $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                    $data_semester = [1, 2];

                    return view('admin.raport.index', compact('filter', 'setting', 'data_angkatan', 'data_semester',  'data_siswa_aktif', 'data_siswa', 'data_guru_mata_pelajaran', 'kelas', 'semester'));
                } else {
                    return redirect()->back()->with('error', 'Data wali kelas tidak ada');
                }
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }

    public function print(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse($request->tanggal_raport ? $request->tanggal_raport : now()->toDateString())->translatedFormat('d F Y');

        $setting = Setting::all()->first();

        if ($setting) {
            $session_tahun_pelajaran = session()->get('raport-tahun_pelajaran');
            $session_kelas = session()->get('raport-kelas');
            $session_semester = session()->get('raport-semester');

            $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

            $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

            $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get()->first();

            if ($wali_kelas) {
                $mpdf = new Mpdf();

                foreach ($data_siswa_aktif as $siswa_aktif) {
                    $data_jenis_mapel = MataPelajaran::all()->unique('jenis')->values()->all();

                    $table_mapel = '';

                    foreach ($data_jenis_mapel as $jenis_mapel) {
                        $table_mapel .= "<tr><td colspan='4' style='border: 0.6px solid #000;padding: 2px 8px;font-weight: bold;'>" . $jenis_mapel->jenis . "</td></tr>";

                        $data_mata_pelajaran = [];

                        foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran) {
                            if ($jenis_mapel->jenis == $guru_mata_pelajaran->mata_pelajaran->jenis) {
                                $jumlah_nilai = 0;
                                $keterangan = '-';

                                foreach ($siswa_aktif->nilai->where('semester', $session_semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $nilai) {
                                    $nilai->update([
                                        'status' => true
                                    ]);

                                    $jumlah_nilai = $nilai->nilai;

                                    $array_keterangan = explode('_', $nilai->keterangan);

                                    $keterangan = count($array_keterangan) == 2 ? $array_keterangan[0] . "<hr style='height: 1px;width: 101.4%;color: #000;margin: 4px;' />" . $array_keterangan[1] : $nilai->keterangan;
                                }

                                array_push($data_mata_pelajaran, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->nama, $jumlah_nilai, $keterangan]);
                            }
                        }

                        sort($data_mata_pelajaran);

                        $no_mapel = 1;
                        foreach ($data_mata_pelajaran as $mata_pelajaran) {
                            $table_mapel .= "<tr>
                                    <td style='border: 0.6px solid #000;;padding: 2px 4px;width: 16px;text-align: center;'>" . $no_mapel .  "</td>
                                    <td style='border: 0.6px solid #000;padding: 2px 4px;width: 128px;'>" . $mata_pelajaran[1] .  "</td>
                                    <td style='border: 0.6px solid #000;padding: 2px 4px;width: 64px;text-align: center;font-size: 10px;'>" . $mata_pelajaran[2] .  "</td>
                                    <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: justify;'>" . $mata_pelajaran[3] .  "</td>
                                    </tr>";

                            $no_mapel++;
                        }
                    }

                    $table_ekskul = '';

                    $ekskul = $siswa_aktif->ekskul->where('semester', $session_semester)->first();



                    if ($ekskul) {

                        $ekskul_nama = explode('_', $ekskul->nama);
                        $ekskul_keterangan = explode('_', $ekskul->keterangan);

                        for ($x = 1; $x <= count($ekskul_nama); $x++) {
                            if ($ekskul_nama[$x - 1] !== "") {
                                $table_ekskul .= "<tr>
                                    <td style='border: 0.6px solid #000;padding: 2px 4px;width: 16px;text-align: center;'>" . $x .  "</td>
                                    <td style='border: 0.6px solid #000;padding: 2px 4px;width: 192px;'>" . $ekskul_nama[$x - 1] .  "</td>
                                    <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: justify;'>" . $ekskul_keterangan[$x - 1] .  "</td>
                                    </tr>";
                            }
                        }
                    }

                    $ketidakhadiran = $siswa_aktif->ketidakhadiran->where('semester', $session_semester)->first();

                    if ($ketidakhadiran) {

                        $table_ketidakhadiran = "<tr>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;width: 148px;text-align: center;text-align: left;'>Sakit</td>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: center;'>"  . $ketidakhadiran->sakit .  " hari</td>
                        </tr>
                        <tr>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;width: 148px;text-align: center;text-align: left;'>Izin</td>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: center;'>"  . $ketidakhadiran->izin .  " hari</td>
                        </tr>
                        <tr>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;width: 148px;text-align: center;text-align: left;'>Tanpa Keterangan</td>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: center;'>"  . $ketidakhadiran->tanpa_keterangan .  " hari</td>
                        </tr>";
                    } else {
                        $table_ketidakhadiran = "<tr>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;width: 148px;text-align: center;text-align: left;'>Sakit</td>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: center;'>... hari</td>
                        </tr>
                        <tr>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;width: 148px;text-align: center;text-align: left;'>Izin</td>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: center;'>... hari</td>
                        </tr>
                        <tr>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;width: 148px;text-align: center;text-align: left;'>Tanpa Keterangan</td>
                            <td style='border: 0.6px solid #000;padding: 2px 4px;text-align: center;'>... hari</td>
                        </tr>";
                    }

                    $table_catatan = "";

                    if ($session_semester == 2) {
                        $table_catatan = "<td style='vertical-align: top;padding-left: 12px;'>
                            <div style='font-family: Arial;font-size: 10px;' ><b>Keputusan:</b><div>
                            <div style='font-family: Arial;font-size: 10px;' >Berdasarkan pencapaian seluruh kompetensi, peserta didik ditetapkan :<div>
                            <div style='padding-top:46px;'></div>
                            <div style='font-family: Arial;font-size: 10px;' ><b>Naik / Tinggal *</b>) Kelas .....<div>
                            <br />
                            <div style='font-family: Arial;font-size: 10px;' ><b>*</b>) Coret yang tidak perlu<div>
                            </td>";
                    }


                    $html = "
                            <html>
                            <head>
                            </head>
                            <body>
                            <div style='padding-top:46px;'></div>
                            <table style='border-collapse:collapse;border-spacing: 0; font-family: Arial;width: 100%;font-size: 8px;'>
                                <thead>
                                    <tr>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;;width: 16px;'>No</th>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;width: 128px;'>Mata Pelajaran</th>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;width: 64px;'>Nilai Akhir</th>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;'>Capaian Kompetensi</th>
                                    </tr>
                                </thead>
                                <tbody>" . $table_mapel . "
                                </tbody>
                            </table>
                            <div style='padding-top: 8px;'></div>
                            <table style='border-collapse:collapse;border-spacing: 0;font-family: Arial;width: 100%;font-size: 8px;'>
                                <thead>
                                    <tr>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;width: 16px;'>No</th>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;width: 192px;'>Ekstrakurikuler</th>
                                        <th style='border: 0.6px solid #000;color: #000;padding: 2px 4px;'>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>" . $table_ekskul . "
                                </tbody>
                            </table>
                            <div style='padding-top: 8px;'></div>
                            <table style='border-collapse:collapse;border-spacing: 0;margin-left: -1.5px;'>
                                <tr>
                                    <td>
                                        <table style='border-collapse:collapse;border-spacing: 0;font-family: Arial;width: 256px;font-size: 8px;'>
                                            <thead>
                                                <tr>
                                                    <th style='border: 0.6px solid #000;color: #000;padding: 4px 8px;' colspan='2'>Ketidakhadiran</th>
                                                </tr>
                                            </thead>
                                            <tbody>" . $table_ketidakhadiran . "
                                            </tbody>
                                        </table>
                                    </td>" .
                        $table_catatan
                        .
                        "</tr>
                            </table>
                            </body>
                            </html>
                            ";

                    $mpdf->showImageErrors = true;
                    $mpdf->WriteHTML($html);
                    // $mpdf->Image(asset('/images/logo-ttd-kepsek.png'), 80, 260, 40, 28, 'png', '', true, false);
                    // NAMA
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(16, 16);
                    $mpdf->WriteCell(6.4, 0.4, 'Nama Peserta Didik', 0, 'C');
                    $mpdf->SetXY(52, 16);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(55, 16);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->siswa->nama, 0, 'C');
                    // NISN
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(16, 19);
                    $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
                    $mpdf->SetXY(52, 19);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(55, 19);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->siswa->nis, 0, 'C');
                    // SEKOLAH
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(16, 22);
                    $mpdf->WriteCell(6.4, 0.4, 'Sekolah', 0, 'C');
                    $mpdf->SetXY(52, 22);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(55, 22);
                    $mpdf->WriteCell(6.4, 0.4, $setting->sekolah, 0, 'C');
                    // ALAMAT
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(16, 25);
                    $mpdf->WriteCell(6.4, 0.4, 'Alamat', 0, 'C');
                    $mpdf->SetXY(52, 25);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(55, 25);
                    $mpdf->MultiCell(60, 0.4, $setting->alamat, 0, 'L');
                    // KELAS
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(120, 16);
                    $mpdf->WriteCell(6.4, 0.4, 'Kelas', 0, 'C');
                    $mpdf->SetXY(160, 16);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(162, 16);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->kelas, 0, 'C');
                    // FASE
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(120, 19);
                    $mpdf->WriteCell(6.4, 0.4, 'Fase', 0, 'C');
                    $mpdf->SetXY(160, 19);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(162, 19);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->angkatan == 'X' ? 'E' : 'F', 0, 'C');
                    // SEMESTER
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(120, 22);
                    $mpdf->WriteCell(6.4, 0.4, 'Semester', 0, 'C');
                    $mpdf->SetXY(160, 22);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(162, 22);
                    $mpdf->WriteCell(6.4, 0.4, $session_semester, 0, 'C');
                    // TAHUN PELAJARAN
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(120, 25);
                    $mpdf->WriteCell(6.4, 0.4, 'Tahun Pelajaran', 0, 'C');
                    $mpdf->SetXY(160, 25);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 7.4);
                    $mpdf->SetXY(162, 25);
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
                    $mpdf->SetXY(82, 262);
                    $mpdf->WriteCell(6.4, 0.4, 'Mengetahui,', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 265);
                    $mpdf->WriteCell(6.4, 0.4, 'Kepala Sekolah', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 279);
                    $mpdf->WriteCell(6.4, 0.4, $setting->kepala_sekolah, 0, 'C');
                    $mpdf->SetXY(82, 284);
                    $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');
                }

                $mpdf->Output('Simaku - Raport Siswa.pdf', 'I');
                exit;

                return redirect()->route('admin.raport')->with('success', 'Cetak Raport telah berhasil ...');
            } else {
                return redirect()->back()->with('error', 'Data wali kelas tidak ada');
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }
}
