@extends('layouts.admin')
@section('nav__item-mata_pelajaran', 'active')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFilter">
                    Filter
                </button>
                <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalImport">
                    Import Excel
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
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tahun Pelajaran</th>
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Guru</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($mata_pelajaran as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->tahun_pelajaran }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->kelas }}</td>
                        <td>{{ $data->guru->nama }}</td>
                        <td>
                            <a href="#modalEdit" data-toggle="modal"
                                onclick="$('#modalEdit #formEdit').attr('action', 'mata_pelajaran/{{$data->id}}/update'); $('#modalEdit #formEdit #kode_guru').attr('value', '{{$data->kode_guru}}'); $('#modalEdit #formEdit #nama').attr('value', '{{$data->nama}}'); $('#modalEdit #formEdit #kelas').attr('value', '{{$data->kelas}}');"
                                class="btn btn-warning">Ubah</a>
                            <a href="#modalDelete" data-toggle="modal"
                                onclick="$('#modalDelete #formDelete').attr('action', 'mata_pelajaran/{{$data->id}}/destroy')"
                                class="btn btn-danger ml-2">Hapus</a>
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
<div class="modal fade" id="modalFilter" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.mata_pelajaran') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Data Mata Pelajaran</h5>
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
                        @foreach($tahun_pelajaran as $data)
                        @if($filter->tahun_pelajaran !== $data)
                        <option value="{{ $data }}">{{ $data }}</option>
                        @endif
                        @endforeach
                        @else
                        @foreach($tahun_pelajaran as $data)
                        <option value="{{ $data }}">{{ $data }}</option>
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
<div class="modal fade" id="modalImport" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Excel Data Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.mata_pelajaran.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama">File</label>
                        <input type="file" class="form-control" required id="excel" name="data_mata_pelajaran"
                            accept=".xlsx, .xls">
                        <div class="text-small text-danger mt-2">
                            * Mohon masukkan data dengan benar sebelum dikirim
                        </div>
                        <a href="{{ route('admin.mata_pelajaran.export_format') }}" class="btn btn-warning mt-4">Unduh
                            Format
                            Import</a>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="">
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
                    </div>
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
                        <label for="guru">Guru <span class="text-danger">*</span></label>
                        <select class="form-control @error('guru') is-invalid @enderror" autocomplete="off" id="guru"
                            name="guru" required>
                            @foreach($guru as $data)
                            <option value="{{ $data->id }}">{{ $data->nama }}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Mata Pelajaran ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form id="formDelete" action="" method="get">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection