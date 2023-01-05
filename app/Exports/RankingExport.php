<?php

namespace App\Exports;

use App\Models\GuruMataPelajaran;
use App\Models\MataPelajaran;
use App\Models\Setting;
use App\Models\SiswaAktif;
use App\Models\WaliKelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;


class RankingExport implements FromView, ShouldAutoSize
{

    protected $tgl_legger;

    function __construct($tanggal_legger)
    {
        $this->tgl_legger = $tanggal_legger;
    }

    public function view(): View
    {
        date_default_timezone_set("Asia/Jakarta");

        $date_now = Carbon::parse(now());

        $date_legger = Carbon::parse($this->tgl_legger ? $this->tgl_legger : now()->toDateString())->translatedFormat('d F Y');

        $setting = Setting::all()->first();

        if ($setting) {
            
            $session_tahun_pelajaran = session()->get('ranking-tahun_pelajaran');
            $session_kelas = session()->get('ranking-kelas');
            $session_semester = session()->get('ranking-semester');
            
            $data_guru_mata_pelajaran = GuruMataPelajaran::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();
            
            $data_siswa_aktif = SiswaAktif::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->get();
            
            $wali_kelas = WaliKelas::where('tahun_pelajaran', $session_tahun_pelajaran)->where('kelas', $session_kelas)->first();

            if($wali_kelas) {   

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
                    $table_mapel .= "<th>" . $nama_mata_pelajaran[1] . "</th>";
                    $table_kkm .= "<td>" . 75 . "</td>";
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
                            $table_nilai .= "<td>" . $total_nilai[1] . "</td>";
                        } else {
                            $table_nilai .= "<td>" . $total_nilai[1] . "</td>";
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

                    $table_siswa .= "<tr><td>" . $no . "</td>
                            <td>" . $siswa_aktif->siswa->nis . "</td>
                            <td>" . $siswa_aktif->siswa->nama . "</td>" . $table_nilai . "<td>" . $jmlh_nilai  . "</td>" . "<td>" . substr($rata_nilai, 0, 4)  . "</td>" . "<td>" . $hasil_ranking  . "</td>" . "<td>" . ($ketidakhadiran ? $ketidakhadiran->sakit . '|' . $ketidakhadiran->izin . '|' . $ketidakhadiran->tanpa_keterangan : '-|-|-') . "</td>"
                        . "</tr>";

                    $no++;
                    $i++;
                }

                return view('admin/ranking/legger-table', compact('setting','session_kelas', 'session_semester', 'session_tahun_pelajaran', 'date_now', 'date_legger', 'wali_kelas', 'table_mapel', 'table_kkm', 'table_siswa'));
            } else {
                return redirect()->back()->with('error', 'Data wali kelas tidak ada');
            }
        } else {
            return redirect()->route('admin.setting')->with('error', 'Isi data setting terlebih dahulu');
        }
    }
}
