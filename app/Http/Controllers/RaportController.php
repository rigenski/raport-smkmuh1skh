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

                    if ($filter->has('semester')) {
                        session(['raport-tahun_pelajaran' => $setting->tahun_pelajaran]);
                        session(['raport-kelas' => $kelas]);
                        session(['raport-semester' => $filter->semester]);

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
                        $table_mapel .= "<tr style='background-color: #e0e0e0;'><td colspan='4' style='border: 0px solid #fff;padding: 3px 8px;vertical-align: top;font-weight: bold;'>" . $jenis_mapel->jenis . "</td></tr>";

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

                                    $keterangan = count($array_keterangan) == 2 ? $array_keterangan[0] . "<hr style='height: 2px;width: 105%;color: #fff;margin: 4px;' />" . $array_keterangan[1] : $nilai->keterangan;
                                }

                                array_push($data_mata_pelajaran, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->nama, $jumlah_nilai, $keterangan]);
                            }
                        }

                        sort($data_mata_pelajaran);

                        $no_mapel = 1;
                        foreach ($data_mata_pelajaran as $mata_pelajaran) {
                            $table_mapel .= "<tr style='background-color: #e0e0e0;'>
                                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 40px;text-align: center;vertical-align: top;'>" . $no_mapel .  "</td>
                                    <td style='border: 0px solid #fff;padding: 3px 8px;vertical-align: top;'>" . $mata_pelajaran[1] .  "</td>
                                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 60px;text-align: center;'>" . $mata_pelajaran[2] .  "</td>
                                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 340px;text-align: justify;vertical-align: top;'>" . $mata_pelajaran[3] .  "</td>
                                    </tr>";

                            $no_mapel++;
                        }
                    }

                    $table_ekskul = '';

                    $ekskul = $siswa_aktif->ekskul->where('semester', $session_semester)->first();

                    if ($ekskul) {

                        $ekskul_nama = explode('_', $ekskul->nama);
                        $ekskul_keterangan = explode('_', $ekskul->keterangan);

                        if (count($ekskul_nama) >= 2) {
                            for ($x = 1; $x <= count($ekskul_nama); $x++) {
                                $table_ekskul .= "<tr style='background-color: #e0e0e0;'>
                                <td style='border: 0px solid #fff;padding: 3px 8px;width: 40px;text-align: center;vertical-align: top;'>" . $x .  "</td>
                                <td style='border: 0px solid #fff;padding: 3px 8px;width: 200px;'>" . $ekskul_nama[$x - 1] .  "</td>
                                <td style='border: 0px solid #fff;padding: 3px 8px;'>" . $ekskul_keterangan[$x - 1] .  "</td>
                                </tr>";
                            }
                        }
                    }

                    $ketidakhadiran = $siswa_aktif->ketidakhadiran->where('semester', $session_semester)->first();

                    if ($ketidakhadiran) {

                        $table_ketidakhadiran = "<tr style='background-color: #e0e0e0;'>
                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Sakit</td>
                    <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>"  . $ketidakhadiran->sakit .  " hari</td>
                </tr>
                <tr style='background-color: #e0e0e0;'>
                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Izin</td>
                    <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>"  . $ketidakhadiran->izin .  " hari</td>
                </tr>
                <tr style='background-color: #e0e0e0;'>
                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Tanpa Keterangan</td>
                    <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>"  . $ketidakhadiran->tanpa_keterangan .  " hari</td>
                </tr>";
                    } else {
                        $table_ketidakhadiran = "<tr style='background-color: #e0e0e0;'>
                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Sakit</td>
                    <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>... hari</td>
                </tr>
                <tr style='background-color: #e0e0e0;'>
                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Izin</td>
                    <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>... hari</td>
                </tr>
                <tr style='background-color: #e0e0e0;'>
                    <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Tanpa Keterangan</td>
                    <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>... hari</td>
                </tr>";
                    }


                    $html = "
                            <html>
                            <head>
                            </head>
                            <body>
                            <div style='padding-top:60px;'></div>
                            <table style='border: 0px solid #fff; font-family: Arial;width: 100%;font-size: 9px;'>
                                <thead>
                                    <tr style='background-color: #adadad;'>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;'>No</th>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Mata Pelajaran</th>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 72px;'>Nilai Akhir</th>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Capaian Kompetensi</th>
                                    </tr>
                                </thead>
                                <tbody>" . $table_mapel . "
                                </tbody>
                            </table>
                            <div style='padding-top: 8px;'></div>
                            <table style='border: 0px solid #fff; font-family: Arial;width: 100%;font-size: 9px;'>
                                <thead>
                                    <tr style='background-color: #adadad;'>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;'>No</th>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 200px;'>Ekstrakurikuler</th>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>" . $table_ekskul . "
                                </tbody>
                            </table>
                            <div style='padding-top: 8px;'></div>
                            <table style='border: 0px solid #fff; font-family: Arial;width: 240px;font-size: 9px;'>
                                <thead>
                                    <tr style='background-color: #adadad;'>
                                        <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;' colspan='2'>Ketidakhadiran</th>
                                    </tr>
                                </thead>
                                <tbody>" . $table_ketidakhadiran . "
                                </tbody>
                            </table>
                            </body>
                            </html>
                            ";

                    $mpdf->WriteHTML($html);
                    // NAMA
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(16, 16);
                    $mpdf->WriteCell(6.4, 0.4, 'Nama Peserta Didik', 0, 'C');
                    $mpdf->SetXY(52, 16);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(55, 16);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->siswa->nama, 0, 'C');
                    // NISN
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(16, 20);
                    $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
                    $mpdf->SetXY(52, 20);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(55, 20);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->siswa->nis, 0, 'C');
                    // SEKOLAH
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(16, 24);
                    $mpdf->WriteCell(6.4, 0.4, 'Sekolah', 0, 'C');
                    $mpdf->SetXY(52, 24);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(55, 24);
                    $mpdf->WriteCell(6.4, 0.4, $setting->sekolah, 0, 'C');
                    // ALAMAT
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(16, 28);
                    $mpdf->WriteCell(6.4, 0.4, 'Alamat', 0, 'C');
                    $mpdf->SetXY(52, 28);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(55, 28);
                    $mpdf->MultiCell(60, 0.4, $setting->alamat, 0, 'L');
                    // KELAS
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(120, 16);
                    $mpdf->WriteCell(6.4, 0.4, 'Kelas', 0, 'C');
                    $mpdf->SetXY(160, 16);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(162, 16);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->kelas, 0, 'C');
                    // FASE
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(120, 20);
                    $mpdf->WriteCell(6.4, 0.4, 'Fase', 0, 'C');
                    $mpdf->SetXY(160, 20);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(162, 20);
                    $mpdf->WriteCell(6.4, 0.4, $siswa_aktif->angkatan == 'X' ? 'E' : 'F', 0, 'C');
                    // SEMESTER
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(120, 24);
                    $mpdf->WriteCell(6.4, 0.4, 'Semester', 0, 'C');
                    $mpdf->SetXY(160, 24);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(162, 24);
                    $mpdf->WriteCell(6.4, 0.4, $session_semester, 0, 'C');
                    // TAHUN PELAJARAN
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(120, 28);
                    $mpdf->WriteCell(6.4, 0.4, 'Tahun Pelajaran', 0, 'C');
                    $mpdf->SetXY(160, 28);
                    $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(162, 28);
                    $mpdf->WriteCell(6.4, 0.4, $session_tahun_pelajaran, 0, 'C');


                    // TTD WALI SISWA
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(20, 240);
                    $mpdf->WriteCell(6.4, 0.4, 'Orang Tua / Wali Siswa', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(20, 252);
                    $mpdf->WriteCell(6.4, 0.4, '............................', 0, 'C');

                    // TTD WALI KELAS
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(140, 236);
                    $mpdf->WriteCell(6.4, 0.4, 'Sukoharjo, ' . $date_now, 0, 'C');
                    $mpdf->SetXY(140, 240);
                    $mpdf->WriteCell(6.4, 0.4, 'Wali Kelas', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(140, 252);
                    $mpdf->WriteCell(6.4, 0.4, $wali_kelas->guru->nama, 0, 'C');
                    $mpdf->SetXY(140, 256);
                    $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');
                    // TTD KEPALA SEKOLAH
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 260);
                    $mpdf->WriteCell(6.4, 0.4, 'Mengetahui,', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 264);
                    $mpdf->WriteCell(6.4, 0.4, 'Kepala Sekolah', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(82, 276);
                    $mpdf->WriteCell(6.4, 0.4, $setting->kepala_sekolah, 0, 'C');
                    $mpdf->SetXY(82, 280);
                    $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');
                }

                $mpdf->Output('Raport Siswa SMK Muhammadiyah 1 Sukoharjo.pdf', 'I');
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
