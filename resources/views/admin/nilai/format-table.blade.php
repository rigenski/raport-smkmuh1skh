<table>
  <thead>
    <tr>
      <th><b>KODE MATA PELAJARAN</b></th>
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
      <td>{{ $mata_pelajaran->kode_mata_pelajaran }}</td>
      <td>{{ $mata_pelajaran->nama_mata_pelajaran }}</td>
      <td>{{ $data->kelas }}</td>
      <td>{{ $data->siswa->nomer_induk_siswa }}</td>
      <td>{{ $data->siswa->nama_siswa }}</td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endforeach
  </tbody>
</table>