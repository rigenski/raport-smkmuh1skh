@extends('layouts.admin')
@section('nav_item-raport_p5', 'active')

@section('title', 'Raport P5')

@if(auth()->user()->role == 'admin')
@section('content')
<div class="card mb-4">
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 my-1">
      <div class="d-flex align-items-start flex-wrap">
        <button type="button" class="btn btn-info mb-2 ml-2" data-toggle="modal" data-target="#modal-filter">
          Filter
        </button>
        <a href={{ route('admin.raport_p5.projek') }} type="button" class="btn btn-warning mb-2 ml-2">
          Edit
        </a>
        <button type="button" class="btn btn-warning mb-2 ml-2" data-toggle="modal" data-target="#modalFormatImport">
            Format Import
        </button>
        <button type="button" class="btn btn-primary mb-2 ml-2" data-toggle="modal" data-target="#modal-import">
            Import
        </button>
        @if(( $filter->has('tahun_pelajaran') || $filter->has('kelas') || $filter->has('semester')) &&
        count($data_siswa_aktif))
        <button type="button" class="btn btn-primary mb-2 ml-2" data-toggle="modal" data-target="#modal-print">
          Print PDF
        </button>
        @else
        <button type="button" class="btn btn-primary mb-2 ml-2" disabled>
            Print PDF
        </button>
        @endif
      </div>
    </div>
    <div class="col-12 col-sm-6 p-0 my-1">
      <div class="d-flex align-items-end flex-column">
        <div>
          @if(session('success'))
          <div class="alert alert-success p-1 px-4 m-0">
            {{ session('success') }}
          </div>
          @elseif(session('error'))
          <div class="alert alert-danger p-1 px-4 m-0">
            {{ session('error') }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    @if( $filter->has('tahun_pelajaran') || $filter->has('kelas') || $filter->has('semester') )
    <div class="mb-4">
      <table class="mb-2">
          <thead>
              <tr>
                  <th colspan="3">
                      <h5 class="text-dark font-weight-bold">INFORMASI</h5>
                  </th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td class="h6">Tahun Pelajaran</td>
                  <td class="h6 px-2">:</td>
                  <td class="h6 text-primary"><b>{{ $filter->tahun_pelajaran }}</b></td>
              </tr>
              <tr>
                  <td class="h6">Kelas</td>
                  <td class="h6 px-2">:</td>
                  <td class="h6 text-primary"><b>{{ $filter->kelas }}</b></td>
              </tr>
              <tr>
                  <td class="h6">Semester</td>
                  <td class="h6 px-2">:</td>
                  <td class="h6 text-primary"><b>{{ $filter->semester }}</b></td>
              </tr>
          </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">RAPORT P5</span>
        TERLEBIH DAHULU</div>
    @endif
    <div class="table-responsive">
      <table class="table table-striped table-bordered data">
        <thead>
          <tr>
            <th scope="col" style="width: 40px;">No</th>
            <th scope="col">NIS</th>
            <th scope="col" >Nama</th>
            <?php $num = 1; ?>
            @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
              <?php $sub_num = 1; ?>
              @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
                <th class="text-nowrap">D <?= $num; ?>.<?= $sub_num; ?></th>
                <?php $sub_num++; ?>
                @endforeach
              <?php $num++; ?>
            @endforeach
          </tr>
        </thead>
        <tbody>
          <?php $count = 1; ?>
          @foreach($data_siswa_aktif as $siswa_aktif)
          <tr>
              <td>
                  <?= $count ?>
              </td>
              <td>{{ $siswa_aktif->siswa->nis }}</td>
              <td>{{ $siswa_aktif->siswa->nama }}</td>
              @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
                @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
                <th class="text-nowrap">{{ count($raport_p5_elemen->nilai_p5->where('siswa_aktif_id', $siswa_aktif->id)->where('semester', $filter->semester)) ? $raport_p5_elemen->nilai_p5->where('siswa_aktif_id', $siswa_aktif->id)->where('semester', $filter->semester)->first()->nilai : '-' }}</th>
                @endforeach
              @endforeach
            </tr>
            <?php $count++; ?>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@section('modal')


<!-- Modal Filter -->
<div class="modal fade" id="modal-filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.raport_p5') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Data Raport </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tahun_pelajaran">Tahun Pelajaran</label>
                    <select class="form-control" autocomplete="off" id="tahun_pelajaran" name="tahun_pelajaran">
                        @if($filter->has('tahun_pelajaran'))
                        <option value="{{ $filter->tahun_pelajaran }}">{{ $filter->tahun_pelajaran }}</option>
                        @foreach($data_tahun_pelajaran as $tahun_pelajaran)
                        @if($filter->tahun_pelajaran !== $tahun_pelajaran)
                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                        @endif
                        @endforeach
                        @else
                        <option value="{{ $setting->tahun_pelajaran }}">{{ $setting->tahun_pelajaran }}</option>
                        @foreach($data_tahun_pelajaran as $tahun_pelajaran)
                        @if($setting->tahun_pelajaran !== $tahun_pelajaran)
                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="angkatan">Angkatan</label>
                    <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                        @foreach($data_angkatan as $angkatan)
                        <option value="{{ $angkatan->angkatan }}">{{ $angkatan->angkatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select class="form-control" autocomplete="off" id="kelas" name="kelas">
                    </select>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select class="form-control" autocomplete="off" name="semester">
                        @if($filter->has('semester'))
                        <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                        @foreach($data_semester as $semester)
                        @if($semester != $filter->semester)
                        <option value="{{ $semester }}">{{ $semester }}</option>
                        @endif
                        @endforeach
                        @else
                        @foreach($data_semester as $semester)
                        <option value="{{ $semester }}">{{ $semester }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

  
<!-- Modal Print -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <form action="{{ route('admin.raport_p5.print') }}" method="get" class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport P5 Kelas <span class="text-primary"> {{
                      $filter->kelas ? $filter->kelas : '' }}</span> - Semester <span class="text-primary"> {{
                      $filter->semester ? $filter->semester
                      : ''
                      }}</span></h5>
              </span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <label for="tanggal_raport">Tanggal Raport <span class="text-danger">*</span></label>
              <input type="date" required class="form-control @error('tanggal_raport') is-invalid @enderror"
                  id="tanggal_raport" name="tanggal_raport" value="">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
              <button type="submit" class="btn btn-primary">Cetak</button>
          </div>
      </form>
  </div>
</div>

<!-- Modal Format Import -->
<div class="modal fade" id="modalFormatImport" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.raport_p5.export_format') }}" method="get">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Format Import <span class="text-primary">Nilai</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tahun_pelajaran">Tahun Pelajaran</label>
                    <select class="form-control" autocomplete="off" id="tahun_pelajaran-export" name="tahun_pelajaran">
                        @if($filter->has('tahun_pelajaran'))
                        <option value="{{ $filter->tahun_pelajaran }}">{{ $filter->tahun_pelajaran }}</option>
                        @foreach($data_tahun_pelajaran as $tahun_pelajaran)
                        @if($filter->tahun_pelajaran !== $tahun_pelajaran)
                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                        @endif
                        @endforeach
                        @else
                        <option value="{{ $setting->tahun_pelajaran }}">{{ $setting->tahun_pelajaran }}</option>
                        @foreach($data_tahun_pelajaran as $tahun_pelajaran)
                        @if($setting->tahun_pelajaran !== $tahun_pelajaran)
                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                        @endif
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="angkatan">Angkatan</label>
                    <select class="form-control" autocomplete="off" id="angkatan-export" name="angkatan">
                        @foreach($data_angkatan as $angkatan)
                        <option value="{{ $angkatan->angkatan }}">{{ $angkatan->angkatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select class="form-control" autocomplete="off" id="kelas-export" name="kelas">
                    </select>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select class="form-control" autocomplete="off" id="semester-export" name="semester">
                        @if($filter->has('semester'))
                        <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                        @foreach($data_semester as $semester)
                        @if($semester != $filter->semester)
                        <option value="{{ $semester }}">{{ $semester }}</option>
                        @endif
                        @endforeach
                        @else
                        @foreach($data_semester as $semester)
                        <option value="{{ $semester }}">{{ $semester }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-warning">Unduh Format Export</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.raport_p5.import') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Data <span
                        class="text-primary">Raport P5</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">File <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" required id="excel" name="data_raport_p5"
                        accept=".xlsx, .xls">
                    <div class="text-small text-danger mt-2">
                        * Mohon masukkan data dengan benar sebelum dikirim
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

@endsection


@section('script')
<script>
    const data_siswa = @json($data_siswa);

    const elTahunPelajaran = document.getElementById('tahun_pelajaran');
    const elAngkatan = document.getElementById('angkatan');
    const elKelas = document.getElementById('kelas');

    const changeKelas = () => {
        const angkatan = elAngkatan.value;
        const tahun_pelajaran = elTahunPelajaran.value;
        
        const filter_tahun_pelajaran = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }
        
        const data_kelas_filter = data_siswa.filter(filter_tahun_pelajaran);

        const filter_angkatan = (data) => {
            return data.angkatan == angkatan;
        }
        
        const data_kelas_filter2 = data_kelas_filter.filter(filter_angkatan);
        
        const kelas_selected = []; 
        
        data_kelas_filter2.map((data) => {
            kelas_selected.push(data.kelas);
        })

        const delete_duplicate = (value, index, self) => {
            return self.indexOf(value) === index;
        }

        const data_kelas_filter3 = kelas_selected.filter(delete_duplicate);

        let kelas_option = '';

        data_kelas_filter3.map((data, i) => {
            kelas_option += `<option value="${data}">${data}</option>`;
        })

        elKelas.innerHTML = kelas_option;
    }

    elTahunPelajaran.addEventListener('change', () => {
        changeKelas();
    })

    elAngkatan.addEventListener('change', () => {
        changeKelas();
    })


    const data_siswa_export = @json($data_siswa);

    const elTahunPelajaranExport = document.getElementById('tahun_pelajaran-export');
    const elAngkatanExport = document.getElementById('angkatan-export');
    const elKelasExport = document.getElementById('kelas-export');

    const changeKelasExport = () => {
        const angkatan = elAngkatanExport.value;
        const tahun_pelajaran = elTahunPelajaranExport.value;
        
        const filter_tahun_pelajaran = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }
        
        const data_kelas_filter = data_siswa_export.filter(filter_tahun_pelajaran);

        const filter_angkatan = (data) => {
            return data.angkatan == angkatan;
        }
        
        const data_kelas_filter2 = data_kelas_filter.filter(filter_angkatan);
        
        const kelas_selected = []; 
        
        data_kelas_filter2.map((data) => {
            kelas_selected.push(data.kelas);
        })

        const delete_duplicate = (value, index, self) => {
            return self.indexOf(value) === index;
        }

        const data_kelas_filter3 = kelas_selected.filter(delete_duplicate);

        let kelas_option = '';

        data_kelas_filter3.map((data, i) => {
            kelas_option += `<option value="${data}">${data}</option>`;
        })

        elKelasExport.innerHTML = kelas_option;
    }

    elTahunPelajaranExport.addEventListener('change', () => {
        changeKelasExport();
    })

    elAngkatanExport.addEventListener('change', () => {
        changeKelasExport();
    })

    window.onload = () => {
        changeKelas();
        changeKelasExport();
    };
</script>
@endsection

@else

{{-- ================== SECTION OTHER ROLE ============= --}}
{{-- ================== SECTION OTHER ROLE ============= --}}
{{-- ================== SECTION OTHER ROLE ============= --}}
{{-- ================== SECTION OTHER ROLE ============= --}}
{{-- ================== SECTION OTHER ROLE ============= --}}
{{-- ================== SECTION OTHER ROLE ============= --}}
{{-- ================== SECTION OTHER ROLE ============= --}}

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-8 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modal-filter">
                    Filter
                </button>
                @if(count($data_siswa_aktif))
                <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modal-print">
                    Print Raport P5
                </button>
                @else
                <button type="button" class="btn btn-primary ml-2" disabled>
                    Print Raport p5
                </button>
                @endif
            </div>
        </div>
        <div class="col-12 col-sm-4 p-0 my-1">
            <div class="d-flex align-items-end flex-column">
                <div>
                    @if(session('success'))
                    <div class="alert alert-success p-1 px-4 m-0">
                        {{ session('success') }}
                    </div>
                    @elseif(session('error'))
                    <div class="alert alert-danger p-1 px-4 m-0">
                        {{ session('error') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="mb-2">
            <thead>
                <tr>
                    <th colspan="3">
                        <h5 class="text-dark font-weight-bold">INFORMASI</h5>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="h6">Tahun Pelajaran</td>
                    <td class="h6 px-2">:</td>
                    <td class="h6 text-primary"><b>{{ $setting->tahun_pelajaran }}</b></td>
                </tr>
                <tr>
                    <td class="h6">Kelas</td>
                    <td class="h6 px-2">:</td>
                    <td class="h6 text-primary"><b>{{ $kelas }}</b></td>
                </tr>
                <tr>
                    <td class="h6">Semester</td>
                    <td class="h6 px-2">:</td>
                    <td class="h6 text-primary"><b>{{ $filter->semester ? $filter->semester : $semester }}</b>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40px;">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <?php $num = 1; ?>
                        @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
                          <?php $sub_num = 1; ?>
                          @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
                            <th class="text-nowrap">D <?= $num; ?>.<?= $sub_num; ?></th>
                            <?php $sub_num++; ?>
                            @endforeach
                          <?php $num++; ?>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($data_siswa_aktif as $siswa_aktif)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $siswa_aktif->siswa->nis }}</td>
                        <td>{{ $siswa_aktif->siswa->nama }}</td>
                        @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
                          @foreach($raport_p5_dimensi->raport_p5_elemen as $raport_p5_elemen)
                            <th class="text-nowrap">-</th>
                            @endforeach
                        @endforeach
                    </tr>
                    <?php $count++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('modal')

<!-- Modal Print -->
<div class="modal fade" id="modal-print" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.raport_p5.print') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span class="text-primary"> {{
                        $kelas }}</span> - Semester <span class="text-primary"> {{
                        $filter->semester ?
                        $filter->semester :
                        '1' }}
                    </span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="tanggal_raport">Tanggal Raport <span class="text-danger">*</span></label>
                <input type="date" required class="form-control @error('tanggal_raport') is-invalid @enderror"
                    id="tanggal_raport" name="tanggal_raport" value="">
            </div>
            <div class="modal-footer">
                <form id="form-delete" action="{{ route('admin.raport_p5.print') }}" method="get">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </form>
            </div>
        </form>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="modal-filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.raport') }}" method="get">
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" autocomplete="off" name="semester">
                            @if($filter->has('semester'))
                            <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                            @foreach($data_semester as $semester)
                            @if($semester != $filter->semester)
                            <option value="{{ $semester }}">{{ $semester }}</option>
                            @endif
                            @endforeach
                            @else
                            @foreach($data_semester as $semester)
                            <option value="{{ $semester }}">{{ $semester }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@endif