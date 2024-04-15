<?php

namespace App\Http\Controllers;

use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\NilaiIjazah;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class TranskripController extends Controller
{
    function decrementYearInString($dateString)
    {
        [$startYear, $endYear] = explode('/', $dateString);

        $startYear--;
        $endYear--;

        return $startYear . '/' . $endYear;
    }

    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()->first();

        if ($setting) {
            if (auth()->user()->role == 'admin') {

                if ($filter->has('tahun_pelajaran') && $filter->has('kelas')) {
                    session(['transkrip-tahun_pelajaran' => $filter->tahun_pelajaran]);
                    session(['transkrip-kelas' => $filter->kelas]);
                    session(['transkrip-semester' => '1']);

                    $tahun_pelajaran_xii = $filter->tahun_pelajaran;
                    $tahun_pelajaran_xi = $this->decrementYearInString($tahun_pelajaran_xii);
                    $tahun_pelajaran_x = $this->decrementYearInString($tahun_pelajaran_xi);

                    $data_siswa_ids = [];

                    $data_siswa_aktif_xii = SiswaAktif::where('tahun_pelajaran', $tahun_pelajaran_xii)->where('kelas', $filter->kelas)->get();

                    foreach ($data_siswa_aktif_xii as $siswa_aktif_xii) {
                        array_push($data_siswa_ids, $siswa_aktif_xii->siswa_id);
                    }

                    $data_siswa_aktif_xi = SiswaAktif::with('siswa')->where('tahun_pelajaran', $tahun_pelajaran_xi)->whereIn('siswa_id', $data_siswa_ids)->get();
                    $data_siswa_aktif_x = SiswaAktif::with('siswa')->where('tahun_pelajaran', $tahun_pelajaran_x)->whereIn('siswa_id', $data_siswa_ids)->get();

                    $data_guru_mata_pelajaran_x = GuruMataPelajaran::where(function ($query) use ($tahun_pelajaran_x, $data_siswa_aktif_x) {
                        $query->where('tahun_pelajaran', $tahun_pelajaran_x)
                            ->where('kelas', $data_siswa_aktif_x->first()->kelas);
                    })
                        ->select('mata_pelajaran_id')
                        ->distinct()
                        ->get();

                    $data_guru_mata_pelajaran_xi = GuruMataPelajaran::Where(function ($query) use ($tahun_pelajaran_xi, $data_siswa_aktif_xi) {
                        $query->where('tahun_pelajaran', $tahun_pelajaran_xi,)
                            ->where('kelas', $data_siswa_aktif_xi->first()->kelas);
                    })
                        ->select('mata_pelajaran_id')
                        ->distinct()
                        ->get();

                    $data_guru_mata_pelajaran_xii = GuruMataPelajaran::Where(function ($query) use ($tahun_pelajaran_xii, $data_siswa_aktif_xii) {
                        $query->where('tahun_pelajaran', $tahun_pelajaran_xii)
                            ->where('kelas', $data_siswa_aktif_xii->first()->kelas);
                    })
                        ->select('mata_pelajaran_id')
                        ->distinct()
                        ->get();

                    $siswa_aktif_ids = [];

                    foreach ($data_siswa_aktif_xii as $siswa_aktif_xii) {
                        array_push($siswa_aktif_ids, $siswa_aktif_xii->id);
                    }

                    $data_mata_pelajaran_ijazah = NilaiIjazah::Where(function ($query) use ($tahun_pelajaran_xii, $siswa_aktif_ids) {
                        $query->where('tahun_pelajaran', $tahun_pelajaran_xii)
                            ->whereIn('siswa_aktif_id', $siswa_aktif_ids);
                    })
                        ->select('mata_pelajaran_id')
                        ->distinct()
                        ->get();
                } else {
                    $data_siswa_aktif_x = [];
                    $data_siswa_aktif_xi = [];
                    $data_siswa_aktif_xii = [];
                    $data_guru_mata_pelajaran_x = [];
                    $data_guru_mata_pelajaran_xi = [];
                    $data_guru_mata_pelajaran_xii = [];
                    $data_mata_pelajaran_ijazah = [];
                }

                $data_siswa = SiswaAktif::all();
                $data_angkatan = SiswaAktif::all()->unique('angkatan')->values()->all();

                $data_semester = [1, 2];

                return view('admin.transkrip.index', compact('filter', 'setting', 'data_angkatan', 'data_semester', 'data_siswa_aktif_xii', 'data_siswa_aktif_xi', 'data_siswa_aktif_x', 'data_siswa', 'data_guru_mata_pelajaran_x', 'data_guru_mata_pelajaran_xi', 'data_guru_mata_pelajaran_xii', 'data_mata_pelajaran_ijazah'));
            } else {
                $wali_kelas = WaliKelas::where('guru_id', auth()->user()->guru->id)->where('tahun_pelajaran', $setting->tahun_pelajaran)->get()->first();

                $semester = 1;

                if ($wali_kelas) {
                    $kelas = $wali_kelas->kelas;

                    session(['transkrip-tahun_pelajaran' => $setting->tahun_pelajaran]);
                    session(['transkrip-kelas' => $kelas]);
                    session(['transkrip-semester' => $filter->semester ? $filter->semester : '1']);

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

                    return view('admin.transkrip.index', compact('filter', 'setting', 'data_angkatan', 'data_semester',  'data_siswa_aktif', 'data_siswa', 'data_guru_mata_pelajaran', 'kelas', 'semester'));
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

        $date_now = Carbon::parse($request->tanggal_transkrip ? $request->tanggal_transkrip : now()->toDateString())->translatedFormat('d F Y');

        $setting = Setting::all()->first();


        if ($setting) {
            $session_tahun_pelajaran = session()->get('transkrip-tahun_pelajaran');
            $session_kelas = session()->get('transkrip-kelas');

            $tahun_pelajaran_xii = $session_tahun_pelajaran;
            $tahun_pelajaran_xi = $this->decrementYearInString($tahun_pelajaran_xii);
            $tahun_pelajaran_x = $this->decrementYearInString($tahun_pelajaran_xi);

            $data_siswa_ids = [];

            $data_siswa_aktif_xii = SiswaAktif::where('tahun_pelajaran', $tahun_pelajaran_xii)->where('kelas', $session_kelas)->get();

            foreach ($data_siswa_aktif_xii as $siswa_aktif_xii) {
                array_push($data_siswa_ids, $siswa_aktif_xii->siswa_id);
            }

            $data_siswa_aktif_xi = SiswaAktif::with('siswa')->where('tahun_pelajaran', $tahun_pelajaran_xi)->whereIn('siswa_id', $data_siswa_ids)->get();
            $data_siswa_aktif_x = SiswaAktif::with('siswa')->where('tahun_pelajaran', $tahun_pelajaran_x)->whereIn('siswa_id', $data_siswa_ids)->get();

            $data_guru_mata_pelajaran = GuruMataPelajaran::where(function ($query) use ($tahun_pelajaran_x, $data_siswa_aktif_x) {
                $query->where('tahun_pelajaran', $tahun_pelajaran_x)
                    ->where('kelas', $data_siswa_aktif_x->first()->kelas);
            })
                ->orWhere(function ($query) use ($tahun_pelajaran_xi, $data_siswa_aktif_xi) {
                    $query->where('tahun_pelajaran', $tahun_pelajaran_xi,)
                        ->where('kelas', $data_siswa_aktif_xi->first()->kelas);
                })
                ->orWhere(function ($query) use ($tahun_pelajaran_xii, $data_siswa_aktif_xii) {
                    $query->where('tahun_pelajaran', $tahun_pelajaran_xii)
                        ->where('kelas', $data_siswa_aktif_xii->first()->kelas);
                })
                ->select('mata_pelajaran_id')
                ->distinct()
                ->get();

            $siswa_aktif_ids = [];
            foreach ($data_siswa_aktif_xii as $siswa_aktif_xii) {
                array_push($siswa_aktif_ids, $siswa_aktif_xii->id);
            }

            $data_mata_pelajaran_ijazah = NilaiIjazah::Where(function ($query) use ($tahun_pelajaran_xii, $siswa_aktif_ids) {
                $query->where('tahun_pelajaran', $tahun_pelajaran_xii)
                    ->whereIn('siswa_aktif_id', $siswa_aktif_ids);
            })
                ->select('mata_pelajaran_id')
                ->distinct()
                ->get();

            $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get()->first();

            $data_semester = [1, 2];
            $data_semester_full = [1, 2, 3, 4, 5, 6];

            if ($wali_kelas) {
                $mpdf = new Mpdf();

                foreach ($data_siswa_aktif_xii as $siswa_aktif_xii) {
                    $data_jenis_mapel = MataPelajaran::all()->unique('jenis')->values()->all();

                    $table_semester = '';

                    foreach ($data_semester_full as $semester) {
                        $table_semester .= "<td style='border: 0.6px solid #000;padding: 4px 8px;text-align: center;'>" . $semester .  "</td>";
                    }

                    $table_mapel = '';

                    foreach ($data_jenis_mapel as $jenis_mapel) {
                        $table_mapel .= "<tr><td colspan='10' style='border: 0.6px solid #000;padding: 4px 8px;font-weight: bold;'>" . $jenis_mapel->jenis . "</td></tr>";

                        $data_rekap_mata_pelajaran = [];
                        $data_mata_pelajaran_ids = [];

                        foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran) {
                            array_push($data_mata_pelajaran_ids, $guru_mata_pelajaran->mata_pelajaran->id);
                        }

                        foreach ($data_mata_pelajaran_ijazah as $mata_pelajaran_ijazah) {
                            array_push($data_mata_pelajaran_ids, $mata_pelajaran_ijazah->mata_pelajaran->id);
                        }

                        $data_mata_pelajaran = MataPelajaran::whereIn('id', $data_mata_pelajaran_ids)->get();

                        foreach ($data_mata_pelajaran as $mata_pelajaran) {
                            if ($jenis_mapel->jenis == $mata_pelajaran->jenis) {
                                $jumlah_nilai_1 = 0;
                                $jumlah_nilai_2 = 0;
                                $jumlah_nilai_3 = 0;
                                $jumlah_nilai_4 = 0;
                                $jumlah_nilai_5 = 0;
                                $jumlah_nilai_6 = 0;

                                foreach ($data_semester as $index => $semester) {
                                    foreach ($data_siswa_aktif_x->where('siswa_id', $siswa_aktif_xii->siswa_id)->first()->nilai->where('semester', $semester)->where('mata_pelajaran_id', $mata_pelajaran->id) as $nilai) {
                                        if ($index === 0) {
                                            $jumlah_nilai_1 = $nilai->nilai ? intval($nilai->nilai) : 0;
                                        } else if ($index === 1) {
                                            $jumlah_nilai_2 = $nilai->nilai ? intval($nilai->nilai) : 0;
                                        }
                                    }
                                }

                                foreach ($data_semester as $index => $semester) {
                                    foreach ($data_siswa_aktif_xi->where('siswa_id', $siswa_aktif_xii->siswa_id)->first()->nilai->where('semester', $semester)->where('mata_pelajaran_id', $mata_pelajaran->id) as $nilai) {
                                        if ($index === 0) {
                                            $jumlah_nilai_3 = $nilai->nilai ? intval($nilai->nilai) : 0;
                                        } else if ($index === 1) {
                                            $jumlah_nilai_4 = $nilai->nilai ? intval($nilai->nilai) : 0;
                                        }
                                    }
                                }

                                foreach ($data_semester as $index => $semester) {
                                    foreach ($siswa_aktif_xii->nilai->where('semester', $semester)->where('mata_pelajaran_id', $mata_pelajaran->id) as $nilai) {
                                        if ($index === 0) {
                                            $jumlah_nilai_5 = $nilai->nilai ? intval($nilai->nilai) : 0;
                                        } else if ($index === 1) {
                                            $jumlah_nilai_6 = $nilai->nilai ? intval($nilai->nilai) : 0;
                                        }
                                    }
                                }

                                $jumlah_nilai_mapel = 0;

                                if ($jumlah_nilai_1 != 0) {
                                    $jumlah_nilai_mapel += 1;
                                }
                                if ($jumlah_nilai_2 != 0) {
                                    $jumlah_nilai_mapel += 1;
                                }
                                if ($jumlah_nilai_3 != 0) {
                                    $jumlah_nilai_mapel += 1;
                                }
                                if ($jumlah_nilai_4 != 0) {
                                    $jumlah_nilai_mapel += 1;
                                }
                                if ($jumlah_nilai_5 != 0) {
                                    $jumlah_nilai_mapel += 1;
                                }
                                if ($jumlah_nilai_6 != 0) {
                                    $jumlah_nilai_mapel += 1;
                                }

                                $jumlah_rata_rata = $jumlah_nilai_mapel != 0 ? round(($jumlah_nilai_1 + $jumlah_nilai_2 + $jumlah_nilai_3 + $jumlah_nilai_4 + $jumlah_nilai_5 + $jumlah_nilai_6) / $jumlah_nilai_mapel) : '-';

                                $nilai_ijazah = 0;
                                foreach ($siswa_aktif_xii->nilai_ijazah->where('mata_pelajaran_id', $mata_pelajaran->id) as $nilai) {
                                    $nilai_ijazah = $nilai->nilai != 0 ? round($nilai->nilai) : '-';
                                }

                                array_push($data_rekap_mata_pelajaran, [$mata_pelajaran->urutan, $mata_pelajaran->nama, $jumlah_nilai_1, $jumlah_nilai_2, $jumlah_nilai_3, $jumlah_nilai_4, $jumlah_nilai_5, $jumlah_nilai_6, $jumlah_rata_rata, $nilai_ijazah]);
                            }
                        }


                        sort($data_rekap_mata_pelajaran);

                        $no_mapel = 1;
                        foreach ($data_rekap_mata_pelajaran as $mata_pelajaran) {
                            $table_nilai = '';

                            foreach ($data_semester_full as $index => $semester_full) {
                                $table_nilai .= "<td style='border: 0.6px solid #000;padding: 4px 8px;text-align: center;'>" . ($mata_pelajaran[$index + 2] != 0 ? round($mata_pelajaran[$index + 2]) : '-') .  "</td>";
                            }

                            $table_mapel .= "<tr>
                                    <td style='border: 0.6px solid #000;padding: 4px 8px;text-align: center;'>" . $no_mapel . "</td>
                                    <td style='border: 0.6px solid #000;padding: 4px 8px;'>" . $mata_pelajaran[1] . "</td>" . $table_nilai .
                                "<td style='border: 0.6px solid #000;padding: 4px 8px;text-align: center;'>" . $mata_pelajaran[8] . "</td>" .
                                "<td style='border: 0.6px solid #000;padding: 4px 8px;text-align: center;'>" . $mata_pelajaran[9] . "</td>" . "
                                    </tr>";

                            $no_mapel++;
                        }
                    }

                    $html = "
                            <html>
                                <head>
                                </head>
                                <body style='font-family: Arial;font-size: 10px;'>
                                    <div style='padding: 86px 0 0 0;'></div>
                                    <div style='margin: 0 0 8px 0;'>
                                        <p style='margin: 0;font-size: 14px;font-weight: bold;text-align:center;'>TRANSKRIP AKADEMIK</p>
                                        <p style='margin: 0;font-size: 14px;font-weight: bold;text-align:center;'>TAHUN PELAJARAN " . $tahun_pelajaran_xii . "</p>
                                    </div>
                                    <div style='margin: 0 0 4px 0;'>
                                        <table style='width: 100%;'>
                                            <tr>
                                                <td style='width: 50%;'>
                                                    <table>
                                                        <tr>
                                                            <td>Nama</td>
                                                            <td>:</td>
                                                            <td>" . $siswa_aktif_xii->siswa->nama . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>NIS</td>
                                                            <td>:</td>
                                                            <td>" . $siswa_aktif_xii->siswa->nis . "</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style='width: 50%;'>
                                                    <table>
                                                        <tr>
                                                            <td>Program Keahlian</td>
                                                            <td>:</td>
                                                            <td>" . $siswa_aktif_xii->jurusan . "</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tahun Lulus</td>
                                                            <td>:</td>
                                                            <td>" . explode('/', $tahun_pelajaran_xii)[1] . "</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div>
                                        <table style='border-collapse:collapse;border-spacing: 0; width: 100%;'>
                                            <thead>
                                                <tr>
                                                    <th rowspan='2' style='border: 0.6px solid #000;color: #000;padding: 4px 8px;width: 16px;'>No</th>
                                                    <th rowspan='2' style='border: 0.6px solid #000;color: #000;padding: 4px 8px;width: 240px;'>Mata Pelajaran / Kompetensi</th>
                                                    <th colspan='6' style='border: 0.6px solid #000;color: #000;padding: 4px 8px;width: auto;'>Raport Semester</th>
                                                    <th rowspan='2' style='border: 0.6px solid #000;color: #000;padding: 4px 8px;width: 80px;'>Rata-Rata <br/>Raport</th>
                                                    <th rowspan='2' style='border: 0.6px solid #000;color: #000;padding: 4px 8px;width: 80px;'>Nilai <br/>Ijazah</th>
                                                </tr>
                                                <tr>
                                                    " . $table_semester . "
                                                </tr>
                                            </thead>
                                            <tbody>" . $table_mapel . "
                                            </tbody>
                                        </table>
                                    </div>
                                </body>
                            </html>
                            ";

                    $mpdf->showImageErrors = true;
                    $mpdf->WriteHTML($html);
                    $mpdf->Image(asset('/images/setting/' . $setting->letterhead), 16, 4, 'auto', 26, 'png', '', true, false);

                    // TTD WALI SISWA
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(20, 253);
                    $mpdf->WriteCell(6.4, 0.4, 'Siswa Yang Bersangkutan', 0, 'C');
                    $mpdf->SetFont('Arial', 'B', 8);
                    $mpdf->SetXY(28, 258);
                    $mpdf->WriteCell(6.4, 0.4, 'LULUS', 0, 'C');

                    // TTD WALI KELAS
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(140, 253);
                    $mpdf->WriteCell(6.4, 0.4, 'Sukoharjo, ' . $date_now, 0, 'C');
                    $mpdf->SetXY(140, 256);
                    $mpdf->WriteCell(6.4, 0.4, 'Kepala Sekolah', 0, 'C');
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(140, 267);
                    $mpdf->WriteCell(6.4, 0.4, $setting->kepala_sekolah, 0, 'C');
                    $mpdf->SetXY(140, 270);
                    $mpdf->WriteCell(6.4, 0.4, 'NIP: -', 0, 'C');

                    // WHITESPACE
                    $mpdf->SetFont('Arial', '', 8);
                    $mpdf->SetXY(20, 280);
                    $mpdf->WriteCell(6.4, 0.4, '', 0, 'C');
                }

                $mpdf->Output('Simaku - Raport Siswa.pdf', 'I');
                exit;

                return redirect()->route('admin.transkrip')->with('success', 'Cetak Raport telah berhasil ...');
            } else {
                return redirect()->back()->with('error', 'Data wali kelas tidak ada');
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }
}
