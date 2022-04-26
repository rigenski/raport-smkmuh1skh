<table>
  <thead>
    <tr>
      <th><b>TAHUN PELAJARAN</b></th>
      <th><b>KODE MAPEL</b></th>
      <th><b>MATA PELAJARAN</b></th>
      <th><b>KELAS</b></th>
      <th><b>NIS</b></th>
      <th><b>NAMA</b></th>
      <th><b>NILAI</b></th>
      <th><b>KETERANGAN</b></th>
      <th><b>SEMESTER</b></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($siswa_aktif as $data)
    <tr>
      <td>{{ $setting->tahun_pelajaran }}</td>
      <td>{{ $mata_pelajaran->kode_mapel }}</td>
      <td>{{ $mata_pelajaran->nama }}</td>
      <td>{{ $data->kelas }}</td>
      <td>{{ $data->siswa->nis }}</td>
      <td>{{ $data->siswa->nama }}</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endforeach
  </tbody>
</table>