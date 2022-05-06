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

class RaportController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role == 'admin') {
            $filter = $request;

            if ($filter->has('tahun_pelajaran') && $filter->has('kelas') && $filter->has('semester')) {
                session(['raport-tahun_pelajaran' => $filter->tahun_pelajaran]);
                session(['raport-kelas' => $filter->kelas]);
                session(['raport-semester' => $filter->semester]);

                $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();

                $guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $filter->tahun_pelajaran)->where('kelas', $filter->kelas)->get();
            } else {
                $siswa_aktif = [];
                $guru_mata_pelajaran = [];
            }

            $siswa = SiswaAktif::all();

            $angkatan = ['X', 'XI', 'XII'];

            $semester = [1, 2];

            return view('admin.raport.index', compact('filter', 'angkatan', 'semester',  'siswa_aktif', 'siswa', 'guru_mata_pelajaran'));
        } else {
            $filter = $request;

            $setting = Setting::all()[0];

            $kelas = auth()->user()->guru->wali_kelas->kelas;

            if ($filter->has('semester')) {
                session(['raport-tahun_pelajaran' => $setting->tahun_pelajaran]);
                session(['raport-kelas' => $kelas]);
                session(['raport-semester' => $filter->semester]);

                $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $kelas)->get();

                $guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $setting->tahun_pelajaran)->where('kelas', $kelas)->get();
            } else {
                $siswa_aktif = [];
                $guru_mata_pelajaran = [];
            }

            $siswa = SiswaAktif::all();

            $angkatan = ['X', 'XI', 'XII'];

            $semester = [1, 2];

            return view('admin.raport.index', compact('filter', 'angkatan', 'semester',  'siswa_aktif', 'siswa', 'guru_mata_pelajaran', 'kelas', 'setting'));
        }
    }

    public function print(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse($request->tanggal_raport ? $request->tanggal_raport : now()->toDateString())->translatedFormat('d F Y');

        $session_tahun_pelajaran = session()->get('raport-tahun_pelajaran');
        $session_kelas = session()->get('raport-kelas');
        $session_semester = session()->get('raport-semester');

        $guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

        $siswa_aktif = SiswaAktif::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();

        $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get()[0];

        $setting = Setting::all()[0];

        $mpdf = new Mpdf();

        foreach ($siswa_aktif as $data) {
            $table_mapel = '';

            $no = 1;

            foreach ($guru_mata_pelajaran as $data_mata_pelajaran) {
                $nilai = 0;
                $keterangan = '-';

                foreach ($data->nilai->where('semester', $session_semester)->where('mata_pelajaran_id', $data_mata_pelajaran->mata_pelajaran->id) as $data_nilai) {
                    $data_nilai->update([
                        'status' => true
                    ]);

                    $nilai = $data_nilai->nilai;

                    $array_keterangan = explode('_', $data_nilai->keterangan);

                    $keterangan = count($array_keterangan) == 2 ? $array_keterangan[0] . "<hr style='height: 2px;width: 105%;color: #fff;margin: 4px;' />" . $array_keterangan[1] : $data_nilai->keterangan;
                }

                $table_mapel .= "<tr style='background-color: #e0e0e0;'>
        <td style='border: 0px solid #fff;padding: 3px 8px;width: 40px;text-align: center;vertical-align: top;'>" . $no .  "</td>
        <td style='border: 0px solid #fff;padding: 3px 8px;vertical-align: top;'>" . $data_mata_pelajaran->mata_pelajaran->nama_mata_pelajaran .  "</td>
        <td style='border: 0px solid #fff;padding: 3px 8px;width: 60px;text-align: center;font-weight: bold;'>" . $nilai .  "</td>
        <td style='border: 0px solid #fff;padding: 3px 8px;width: 340px;text-align: justify;vertical-align: top;'>" . $keterangan .  "</td>
    </tr>";

                $no++;
            }

            $html = "
                    <html>
                    <head>
                    </head>
                    <body>
                    <div style='padding-top: 80px;'></div>
                    <table style='border: 0px solid #fff; font-family: Arial;width: 100%;font-size: 10px;'>
                        <thead>
                            <tr style='background-color: #adadad;'>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;'>No</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Mata Pelajaran</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 80px;'>Nilai Akhir</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>" . $table_mapel . "
                        </tbody>
                    </table>
                    <div style='padding-top: 8px;'></div>
                    <table style='border: 0px solid #fff; font-family: Arial;width: 100%;font-size: 10px;'>
                        <thead>
                            <tr style='background-color: #adadad;'>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;'>No</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 200px;'>Ekstrakurikuler</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div style='padding-top: 8px;'></div>
                    <table style='border: 0px solid #fff; font-family: Arial;width: 240px;font-size: 10px;'>
                        <thead>
                            <tr style='background-color: #adadad;'>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;' colspan='2'>Ketidakhadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style='background-color: #e0e0e0;'>
                                <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Sakit</td>
                                <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>...hari</td>
                            </tr>
                            <tr style='background-color: #e0e0e0;'>
                                <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Izin</td>
                                <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>...hari</td>
                            </tr>
                            <tr style='background-color: #e0e0e0;'>
                                <td style='border: 0px solid #fff;padding: 3px 8px;width: 140px;text-align: center;text-align: left;'>Tanpa Keterangan</td>
                                <td style='border: 0px solid #fff;padding: 3px 8px;text-align: center;'>...hari</td>
                            </tr>
                        </tbody>
                    </table>
                    </body>
                    </html>
                    ";

            $mpdf->WriteHTML($html);
            // NAMA
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 16);
            $mpdf->WriteCell(6.4, 0.4, 'Nama Peserta Didik', 0, 'C');
            $mpdf->SetXY(52, 16);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 16);
            $mpdf->WriteCell(6.4, 0.4, $data->siswa->nama_siswa, 0, 'C');
            // NISN
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 21);
            $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
            $mpdf->SetXY(52, 21);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 21);
            $mpdf->WriteCell(6.4, 0.4, $data->siswa->nomer_induk_siswa, 0, 'C');
            // SEKOLAH
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 26);
            $mpdf->WriteCell(6.4, 0.4, 'Sekolah', 0, 'C');
            $mpdf->SetXY(52, 26);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 26);
            $mpdf->WriteCell(6.4, 0.4, $setting->sekolah, 0, 'C');
            // ALAMAT
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 31);
            $mpdf->WriteCell(6.4, 0.4, 'Alamat', 0, 'C');
            $mpdf->SetXY(52, 31);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 31);
            $mpdf->MultiCell(60, 0.4, $setting->alamat, 0, 'L');
            // KELAS
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 16);
            $mpdf->WriteCell(6.4, 0.4, 'Kelas', 0, 'C');
            $mpdf->SetXY(160, 16);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 16);
            $mpdf->WriteCell(6.4, 0.4, $data->kelas, 0, 'C');
            // FASE
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 21);
            $mpdf->WriteCell(6.4, 0.4, 'Fase', 0, 'C');
            $mpdf->SetXY(160, 21);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 21);
            $mpdf->WriteCell(6.4, 0.4, $data->angkatan == 'X' ? 'E' : 'F', 0, 'C');
            // SEMESTER
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 26);
            $mpdf->WriteCell(6.4, 0.4, 'Semester', 0, 'C');
            $mpdf->SetXY(160, 26);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 26);
            $mpdf->WriteCell(6.4, 0.4, $session_semester, 0, 'C');
            // TAHUN PELAJARAN
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 31);
            $mpdf->WriteCell(6.4, 0.4, 'Tahun Pelajaran', 0, 'C');
            $mpdf->SetXY(160, 31);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 31);
            $mpdf->WriteCell(6.4, 0.4, $session_tahun_pelajaran, 0, 'C');


            // TTD WALI SISWA
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(20, 245);
            $mpdf->WriteCell(6.4, 0.4, 'Mengetahui,', 0, 'C');
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(20, 250);
            $mpdf->WriteCell(6.4, 0.4, 'Orang Tua / Wali Siswa', 0, 'C');
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(20, 270);
            $mpdf->WriteCell(6.4, 0.4, '............................', 0, 'C');

            // TTD WALI KELAS
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(140, 245);
            $mpdf->WriteCell(6.4, 0.4, 'Sukoharjo, ' . $date_now, 0, 'C');
            $mpdf->SetXY(140, 250);
            $mpdf->WriteCell(6.4, 0.4, 'Wali Kelas', 0, 'C');
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(140, 270);
            $mpdf->WriteCell(6.4, 0.4, $wali_kelas->guru->nama_guru, 0, 'C');
            $mpdf->SetXY(140, 275);
            $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');
            // TTD KEPALA SEKOLAH
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(82, 275);
            $mpdf->WriteCell(6.4, 0.4, $setting->kepala_sekolah, 0, 'C');
            $mpdf->SetXY(82, 280);
            $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');
        }

        $mpdf->Output('Raport Siswa SMK Muhammadiyah 1 Sukoharjo.pdf', 'I');
        exit;

        return redirect()->route('admin.raport')->with('success', 'Cetak Raport telah berhasil ...');
    }
}
