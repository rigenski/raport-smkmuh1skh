<table>
  <thead>
    <tr>
      <th><b>KELAS</b></th>
      <th><b>NIS</b></th>
      <th><b>NAMA</b></th>
      <?php $num = 1; ?>
      @foreach($data_raport_p5_projek as $raport_p5_projek)
        @foreach($raport_p5_projek->raport_p5_dimensi as $raport_p5_dimensi)
          <?php $sub_num = 1; ?>
            @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
            <th><b>D <?= $num; ?>.<?= $sub_num; ?></b></th>
            <?php $sub_num++; ?>
            @endforeach
          <?php $num++; ?>
        @endforeach
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
      @foreach($data_raport_p5_projek as $raport_p5_projek)
        @foreach($raport_p5_projek->raport_p5_dimensi as $raport_p5_dimensi)
          @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
          <th></th>
          @endforeach
        @endforeach
      @endforeach
      <td>{{ $semester }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<table>
  <thead>
    <tr>
      <th colspan="12"><b>CATATAN</b></th>
    </tr>
  </thead>
  <tbody>
      <?php $num = 1; ?>
      @foreach($data_raport_p5_projek as $raport_p5_projek)
        @foreach($raport_p5_projek->raport_p5_dimensi as $raport_p5_dimensi)
          <?php $sub_num = 1; ?>
            @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
            <tr>
              <td><b>D <?= $num; ?>.<?= $sub_num; ?></b></td>
              <td colspan="11">{{ $raport_p5_elemen->sub_elemen }}</td>
            </tr>
            <?php $num++; ?>
            @endforeach
          <?php $num++; ?>
        @endforeach
      @endforeach
  </tbody>
</table>