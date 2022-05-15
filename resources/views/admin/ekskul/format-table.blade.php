<table>
  <thead>
    <tr>
      <th><b>KELAS</b></th>
      <th><b>NIS</b></th>
      <th><b>NAMA</b></th>
      <th><b>EKSKUL</b></th>
      <th><b>KETERANGAN</b></th>
      <th><b>SEMESTER</b></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($siswa_aktif as $data)
    <tr>
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