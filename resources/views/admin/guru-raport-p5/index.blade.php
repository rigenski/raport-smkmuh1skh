@extends('layouts.admin')
@section('nav_item-guru_raport_p5', 'active')

@section('title', 'Guru Raport P5')

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-filter">
                    Filter
                </button>
                <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modal-import">
                    Import
                </button>
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
        @if( $filter->has('tahun_pelajaran') && $filter->has('semester') )
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
                        <td class="h6">Semester</td>
                        <td class="h6 px-2">:</td>
                        <td class="h6 text-primary"><b>{{ $filter->semester }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">GURU RAPORT P5</span>
            TERLEBIH DAHULU</div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40px;">No</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Semester</th>
                        <th scope="col">Guru</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($data_guru_raport_p5 as $guru_raport_p5)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $guru_raport_p5->kelas }}</td>
                        <td>{{ $guru_raport_p5->semester }}</td>
                        <td>{{ $guru_raport_p5->guru->nama }}</td>
                        <td>
                            <a href="#modal-edit" data-toggle="modal"
                                onclick="$('#modal-edit #form-edit').attr('action', 'guru-raport-p5/{{$guru_raport_p5->id}}/update'); $('#modal-edit #form-edit #kelas').attr('value', '{{$guru_raport_p5->kelas}}'); $('#modal-edit #form-edit #semester').attr('value', '{{$guru_raport_p5->semester}}'); $('#modal-edit #form-edit #semester').text('{{$guru_raport_p5->semester}}'); $('#modal-edit #form-edit #guru').attr('value', '{{$guru_raport_p5->guru->id}}'); $('#modal-edit #form-edit #guru').text('{{$guru_raport_p5->guru->nama}}');"
                                class="btn btn-warning m-1">Ubah</a>
                            <a href="#modal-delete" data-toggle="modal"
                                onclick="$('#modal-delete #form-delete').attr('action', 'guru-raport-p5/{{$guru_raport_p5->id}}/destroy')"
                                class="btn btn-danger m-1">Hapus</a>
                        </td>
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
        <form action="{{ route('admin.guru_raport_p5') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span class="text-primary">Guru Raport P5</span></h5>
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
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.guru_raport_p5.import') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Data <span class="text-primary">Guru Raport P5</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama">File <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="data_guru_raport_p5"
                            name="data_guru_raport_p5" accept=".xlsx, .xls">
                        <label class="custom-file-label" for="data_guru_raport_p5">Pilih File</label>
                    </div>
                    <div class="text-small text-danger mt-2">
                        * Mohon masukkan data dengan benar sebelum dikirim
                    </div>
                    <a href="{{ route('admin.guru_raport_p5.export_format') }}" class="btn btn-warning mt-4">Unduh
                        Format
                        Import</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-edit" class="modal-content" action="" method="post">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary">Guru Raport P5</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kelas">Kelas <span class="text-danger">*</span></label>
                    <input type="text" required class="form-control @error('kelas') is-invalid @enderror" id="kelas"
                        name="kelas" value="">
                    @error('kelas')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="semester">Semester <span class="text-danger">*</span></label>
                    <select class="form-control @error('semester') is-invalid @enderror" autocomplete="off"
                        name="semester" required>
                        <option value="" id="semester"></option>
                        @foreach($data_semester as $semester)
                        <option value="{{ $semester }}">{{ $semester }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="guru">Guru <span class="text-danger">*</span></label>
                    <select class="form-control @error('guru') is-invalid @enderror" autocomplete="off" name="guru"
                        required>
                        <option value="" id="guru"></option>
                        @foreach($data_guru as $guru)
                        <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus <span class="text-primary"> Guru Raport P5</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form id="form-delete" action="" method="get">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection