<table>
    <tbody>
        <tr>
            <td colspan="3">{{ strtoupper($setting->sekolah) }}
            <td>
            <td></td>
            <td colspan="15">
                {{ strtoupper('DAFTAR NILAI RAPORT SEMESTER ' . ($session_semester == 1 ? 'GASAL ' : 'GENAP ') . $session_tahun_pelajaran) }}
            <td>
        </tr>
        <tr>
            <td colspan="3">{{ $setting->alamat }}
            <td>
            <td></td>
            <td colspan="15">{{ 'Kelas: ' . $session_kelas . ', Wali Kelas: ' . $wali_kelas->guru->nama }}
            <td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>NO</td>
            <td>NIS</td>
            <td>NAMA SISWA</td>
            <?= htmlspecialchars_decode($table_mapel) ?>
            <td>JMLH</td>
            <td>RATA</td>
            <td>RANK</td>
            <td>S-I-A</td>
        </tr>
        <tr>
            <td colspan="3">KKM</td>
            <?= htmlspecialchars_decode($table_kkm) ?>
            <td colspan="4"></td>
        </tr>
        <?= htmlspecialchars_decode($table_siswa) ?>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td colspan="3">
                {{ 'Cetak: ' . $date_now->translatedFormat('d F Y') . ', ' . $date_now->toTimeString() }}
            </td>
            <td></td>
            <td></td>
            <td colspan="8">
                {{ 'Sukoharjo, ' . $date_legger }}
            </td>
        </tr>
    </tbody>
</table>
