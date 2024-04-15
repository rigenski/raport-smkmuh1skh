<table>
    <thead>
        <tr>
            <th><b>KODE MAPEL</b></th>
            <th><b>NAMA MAPEL</b></th>
            <th><b>KELAS</b></th>
            <th><b>NIS</b></th>
            <th><b>NAMA</b></th>
            <th><b>NILAI</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa_aktif as $data)
            <tr>
                <td>{{ $mata_pelajaran->kode }}</td>
                <td>{{ $mata_pelajaran->nama }}</td>
                <td>{{ $data->kelas }}</td>
                <td>{{ $data->siswa->nis }}</td>
                <td>{{ $data->siswa->nama }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
