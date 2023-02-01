<table>
  <thead>
    <tr>
      <th><b>KELAS</b></th>
      <th><b>NIS</b></th>
      <th><b>NAMA</b></th>
      <?php $num = 1; ?>
      @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
        <?php $sub_num = 1; ?>
        @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
          <th><b>D <?= $num; ?>.<?= $sub_num; ?></b></th>
          <?php $sub_num++; ?>
          @endforeach
        <?php $num++; ?>
      @endforeach
      <th><b>SEMESTER</b></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($siswa_aktif as $data)
    <tr>
      <td>{{ $data->kelas }}</td>
      <td>{{ $data->siswa->nis }}</td>
      <td>{{ $data->siswa->nama }}</td>
      @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
        @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
          <th></th>
          @endforeach
      @endforeach
      <td></td>
    </tr>
    @endforeach
  </tbody>
</table>