<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Setting;
use App\Models\Siswa;
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

            if ($filter->has('tahun_pelajaran') && $filter->has('angkatan') && $filter->has('kelas') && $filter->has('semester')) {
                $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('angkatan', $filter->angkatan)->where('kelas', $filter->kelas)->unique('siswa_id')->values()->all();

                session(['raport-tahun_pelajaran' => $filter->tahun_pelajaran]);
                session(['raport-angkatan' => $filter->angkatan]);
                session(['raport-kelas' => $filter->kelas]);
                session(['raport-semester' => $filter->semester]);
            } else {
                $siswa = [];

                session(['raport-tahun_pelajaran' => null]);
                session(['raport-angkatan' => null]);
                session(['raport-kelas' => null]);
                session(['raport-semester' => null]);
            }

            $nilai = Nilai::all();

            $semester = [1, 2];

            $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

            return view('admin.raport.index', compact('filter', 'siswa', 'nilai', 'tahun_pelajaran', 'semester'));
        } else {
            $filter = $request;

            $kelas = auth()->user()->guru->wali_kelas->kelas;

            if ($request->has('semester')) {
                $siswa = Siswa::where('kelas', $kelas)->where('jurusan', $kelas[1])->get();

                $siswa = Nilai::all()->where('tahun_pelajaran', $filter->tahun_pelajaran)->where('semester', $filter->semester)->where('angkatan', $filter->angkatan)->where('kelas', $filter->kelas)->unique('siswa_id')->values()->all();

                session(['raport-semester' => $request->semester]);
                session(['raport-kelas' => $request->kelas]);
            } else {
                $siswa = [];

                session(['raport-semester' => null]);
                session(['raport-kelas' => null]);
            }

            $semester = [1, 2];

            return view('admin.raport.index', compact('filter', 'siswa', 'semester'));
        }
    }

    public function print()
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse(now()->toDateString())->translatedFormat('d F Y');

        $session_tahun_pelajaran = session()->get('raport-tahun_pelajaran');
        $session_angkatan = session()->get('raport-angkatan');
        $session_semester = session()->get('raport-semester');
        $session_kelas = session()->get('raport-kelas');

        if ($session_kelas) {
            $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get()[0];
        }

        $siswa = Nilai::all()->where('tahun_pelajaran', $session_tahun_pelajaran)->where('semester', $session_semester)->where('angkatan', $session_angkatan)->where('kelas', $session_kelas)->unique('siswa_id')->values()->all();

        $mpdf = new Mpdf();

        foreach ($siswa as $data) {
            $setting = Setting::all()[0];

            $el_table = '';

            if ($session_semester) {
                $no = 1;

                foreach ($data->siswa->nilai as $nilai) {
                    $nilai->update([
                        'status' => true
                    ]);

                    $keterangan = explode('-', $nilai->keterangan);

                    $el_keterangan = count($keterangan) == 2 ? $keterangan[0] . '<br /><br />' . $keterangan[1] : $nilai->keterangan;

                    $el_table .= "<tr style='background-color: #FFDED2;'>
            <td style='border: 0px solid #fff;padding: 6px 8px;width: 40px;text-align: center;vertical-align: top;'>" . $no .  "</td>
            <td style='border: 0px solid #fff;padding: 6px 8px;vertical-align: top;'>" . $nilai->mata_pelajaran .  "</td>
            <td style='border: 0px solid #fff;padding: 12px 8px;width: 60px;text-align: center;font-weight: bold;'>" . $nilai->nilai .  "</td>
            <td style='border: 0px solid #fff;padding: 6px 8px;width: 340px;vertical-align: top;'>" . $el_keterangan .  "</td>
        </tr>";

                    $no++;
                }


                $nilai_siswa = Nilai::where('siswa_id', $data->siswa->id)->where('semester', $session_semester)->get();
                $nilai_siswa = $nilai_siswa[0];
            }

            $html = "
                    <html>
                    <head>
                    </head>
                    <body>
                    <div style='padding-top: 120px;'></div>
                    <table style='border: 0px solid #fff; font-family: Arial;width: 100%;font-size: 10px;'>
                        <thead>
                            <tr style='background-color: #F58560;'>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;'>No</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Mata Pelajaran</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 70px;'>Nilai Akhir</th>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;'>Capaian Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>" . $el_table . "
                        </tbody>
                    </table>
                    <div style='padding-top: 8px;'></div>
                    <table style='border: 0px solid #fff; font-family: Arial;width: 100%;font-size: 10px;'>
                        <thead>
                            <tr style='background-color: #F58560;'>
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
                            <tr style='background-color: #F58560;'>
                                <th style='border: 0px solid #fff;color: #fff;padding: 4px 8px;width: 40px;' colspan='2'>Ketidakhadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style='background-color: #FFDED2;'>
                                <td style='border: 0px solid #fff;padding: 6px 8px;width: 140px;text-align: center;text-align: left;'>Sakit</td>
                                <td style='border: 0px solid #fff;padding: 6px 8px;text-align: center;'>...hari</td>
                            </tr>
                            <tr style='background-color: #FFDED2;'>
                                <td style='border: 0px solid #fff;padding: 6px 8px;width: 140px;text-align: center;text-align: left;'>Izin</td>
                                <td style='border: 0px solid #fff;padding: 6px 8px;text-align: center;'>...hari</td>
                            </tr>
                            <tr style='background-color: #FFDED2;'>
                                <td style='border: 0px solid #fff;padding: 6px 8px;width: 140px;text-align: center;text-align: left;'>Tanpa Keterangan</td>
                                <td style='border: 0px solid #fff;padding: 6px 8px;text-align: center;'>...hari</td>
                            </tr>
                        </tbody>
                    </table>
                    </body>
                    </html>
                    ";

            $mpdf->WriteHTML($html);
            // NAMA
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 25);
            $mpdf->WriteCell(6.4, 0.4, 'Nama Peserta Didik', 0, 'C');
            $mpdf->SetXY(52, 25);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 25);
            $mpdf->WriteCell(6.4, 0.4, $data->siswa->nama, 0, 'C');
            // NISN
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 30);
            $mpdf->WriteCell(6.4, 0.4, 'NIS', 0, 'C');
            $mpdf->SetXY(52, 30);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 30);
            $mpdf->WriteCell(6.4, 0.4, $data->siswa->nis, 0, 'C');
            // SEKOLAH
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 35);
            $mpdf->WriteCell(6.4, 0.4, 'Sekolah', 0, 'C');
            $mpdf->SetXY(52, 35);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 35);
            $mpdf->WriteCell(6.4, 0.4, $setting->sekolah, 0, 'C');
            // ALAMAT
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(16, 40);
            $mpdf->WriteCell(6.4, 0.4, 'Alamat', 0, 'C');
            $mpdf->SetXY(52, 40);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(55, 38);
            $mpdf->MultiCell(60, 4.8, $setting->alamat, 0, 'L');
            // KELAS
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 25);
            $mpdf->WriteCell(6.4, 0.4, 'Kelas', 0, 'C');
            $mpdf->SetXY(160, 25);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 25);
            $mpdf->WriteCell(6.4, 0.4, $data->kelas, 0, 'C');
            // FASE
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 30);
            $mpdf->WriteCell(6.4, 0.4, 'Fase', 0, 'C');
            $mpdf->SetXY(160, 30);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 30);
            $mpdf->WriteCell(6.4, 0.4, $data->angkatan == 'X' ? 'E' : 'F', 0, 'C');
            // SEMESTER
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 35);
            $mpdf->WriteCell(6.4, 0.4, 'Semester', 0, 'C');
            $mpdf->SetXY(160, 35);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 35);
            $mpdf->WriteCell(6.4, 0.4, $data->semester, 0, 'C');
            // TAHUN PELAJARAN
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(120, 40);
            $mpdf->WriteCell(6.4, 0.4, 'Tahun Pelajaran', 0, 'C');
            $mpdf->SetXY(160, 40);
            $mpdf->WriteCell(6.4, 0.4, ':', 0, 'C');
            $mpdf->SetFont('Arial', '', 10);
            $mpdf->SetXY(162, 40);
            $mpdf->WriteCell(6.4, 0.4, $data->tahun_pelajaran, 0, 'C');


            // TTD WALI SISWA
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(20, 240);
            $mpdf->WriteCell(6.4, 0.4, 'Mengetahui,', 0, 'C');
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(20, 245);
            $mpdf->WriteCell(6.4, 0.4, 'Orang Tua / Wali Siswa', 0, 'C');

            // TTD WALI KELAS
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(140, 240);
            $mpdf->WriteCell(6.4, 0.4, 'Sukoharjo, ' . $date_now, 0, 'C');
            $mpdf->SetXY(140, 245);
            $mpdf->WriteCell(6.4, 0.4, 'Wali Kelas', 0, 'C');
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(140, 265);
            $mpdf->WriteCell(6.4, 0.4,  auth()->user()->role == 'guru' ? auth()->user()->guru->nama : $wali_kelas->guru->nama, 0, 'C');
            // TTD KEPALA SEKOLAH
            $mpdf->SetFont('Arial', 'B', 9.2);
            $mpdf->SetXY(82, 272);
            $mpdf->WriteCell(6.4, 0.4, $setting->kepala_sekolah, 0, 'C');
        }

        $mpdf->Output('Raport Siswa SMK Muhammadiyah 1 Sukoharjo.pdf', 'I');
        exit;

        return redirect()->route('admin.raport')->with('success', 'Cetak Raport telah berhasil ...');
    }
}
