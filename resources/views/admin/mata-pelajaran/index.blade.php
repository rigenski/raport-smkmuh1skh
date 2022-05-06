@extends('layouts.admin')
@section('nav_item-mata_pelajaran', 'active')

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
                        <th scope="col">Jenis Mata Pelajaran</th>
                        <th scope="col">Kode Mata Pelajaran</th>
                        <th scope="col">Nama Mata Pelajaran</th>
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
                        <td>{{ $data->jenis_mata_pelajaran }}</td>
                        <td>{{ $data->kode_mata_pelajaran }}</td>
                        <td>{{ $data->nama_mata_pelajaran }}</td>
                        <td>
                            <a href="#modalEdit" data-toggle="modal"
                                onclick="$('#modalEdit #formEdit').attr('action', 'mata-pelajaran/{{$data->id}}/update'); $('#modalEdit #formEdit #jenis_mata_pelajaran').attr('value', '{{$data->jenis_mata_pelajaran}}'); $('#modalEdit #formEdit #kode_mata_pelajaran').attr('value', '{{$data->kode_mata_pelajaran}}'); $('#modalEdit #formEdit #nama_mata_pelajaran').attr('value', '{{$data->nama_mata_pelajaran}}');"
                                class="btn btn-warning m-1">Ubah</a>
                            <a href="#modalDelete" data-toggle="modal"
                                onclick="$('#modalDelete #formDelete').attr('action', 'mata-pelajaran/{{$data->id}}/destroy')"
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
                    <label for="jenis_mata_pelajaran">Jenis Mata Pelajaran</label>
                    <select class="form-control" autocomplete="off" id="jenis_mata_pelajaran"
                        name="jenis_mata_pelajaran">
                        @if($filter->has('jenis_mata_pelajaran'))
                        <option value="{{ $filter->jenis_mata_pelajaran }}">{{ $filter->jenis_mata_pelajaran }}</option>
                        @foreach($jenis_mata_pelajaran as $data)
                        @if($filter->jenis_mata_pelajaran !== $data->jenis_mata_pelajaran)
                        <option value="{{ $data->jenis_mata_pelajaran }}">{{ $data->jenis_mata_pelajaran }}</option>
                        @endif
                        @endforeach
                        @else
                        @foreach($jenis_mata_pelajaran as $data)
                        <option value="{{ $data->jenis_mata_pelajaran }}">{{ $data->jenis_mata_pelajaran }}</option>
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
        <form class="modal-content" action="{{ route('admin.mata_pelajaran.import') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Excel Data Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="data_mata_pelajaran">File</label>
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
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEdit" class="modal-content" action="" method="post">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Mata Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jenis_mata_pelajaran">Jenis <span class="text-danger">*</span></label>
                    <input type="text" required class="form-control @error('jenis_mata_pelajaran') is-invalid @enderror"
                        id="jenis_mata_pelajaran" name="jenis_mata_pelajaran" value="">
                    @error('jenis_mata_pelajaran')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kode_mata_pelajaran">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                    <input type="text" required class="form-control @error('kode_mata_pelajaran') is-invalid @enderror"
                        id="kode_mata_pelajaran" name="kode_mata_pelajaran" value="">
                    @error('kode_mata_pelajaran')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nama_mata_pelajaran">Nama Mata Pelajaran<span class="text-danger">*</span></label>
                    <input type="text" required class="form-control @error('nama_mata_pelajaran') is-invalid @enderror"
                        id="nama_mata_pelajaran" name="nama_mata_pelajaran" value="">
                    @error('nama_mata_pelajaran')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formDelete" class="modal-content" action="" method="get">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Mata Pelajaran ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </form>
    </div>
</div>
@endsection