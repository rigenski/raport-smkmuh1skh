@extends('layouts.admin')
@section('nav_item-mata_pelajaran', 'active')

@section('title', 'Mata Pelajaran')

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
        @if( $filter->has('jenis') )
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
                        <td class="h6">Jenis</td>
                        <td class="h6 px-2">:</td>
                        <td class="h6 text-primary"><b>{{ $filter->jenis }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40px;">No</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Kode</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Urutan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($data_mata_pelajaran as $mata_pelajaran)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $mata_pelajaran->jenis }}</td>
                        <td>{{ $mata_pelajaran->kode }}</td>
                        <td>{{ $mata_pelajaran->nama }}</td>
                        <td>{{ $mata_pelajaran->urutan }}</td>
                        <td>
                            <a href="#modal-edit" data-toggle="modal"
                                onclick="$('#modal-edit #form-edit').attr('action', 'mata-pelajaran/{{$mata_pelajaran->id}}/update'); $('#modal-edit #form-edit #jenis').attr('value', '{{$mata_pelajaran->jenis}}'); $('#modal-edit #form-edit #kode').attr('value', '{{$mata_pelajaran->kode}}'); $('#modal-edit #form-edit #nama').attr('value', '{{$mata_pelajaran->nama}}'); $('#modal-edit #form-edit #urutan').attr('value', '{{$mata_pelajaran->urutan}}'); $('#modal-edit #form-edit #urutan').text('{{$mata_pelajaran->urutan}}');"
                                class="btn btn-warning m-1">Ubah</a>
                            <a href="#modal-delete" data-toggle="modal"
                                onclick="$('#modal-delete #form-delete').attr('action', 'mata-pelajaran/{{$mata_pelajaran->id}}/destroy')"
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
        <form action="{{ route('admin.mata_pelajaran') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span class="text-primary"> Mata
                        Pelajaran</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jenis">Jenis</label>
                    <select class="form-control" autocomplete="off" id="jenis" name="jenis">
                        @if($filter->has('jenis'))
                        <option value="{{ $filter->jenis }}">{{ $filter->jenis }}</option>
                        @foreach($data_jenis_mata_pelajaran as $jenis_mata_pelajaran)
                        @if($filter->jenis !== $jenis_mata_pelajaran->jenis)
                        <option value="{{ $jenis_mata_pelajaran->jenis }}">{{ $jenis_mata_pelajaran->jenis }}</option>
                        @endif
                        @endforeach
                        @else
                        @foreach($data_jenis_mata_pelajaran as $jenis_mata_pelajaran)
                        <option value="{{ $jenis_mata_pelajaran->jenis }}">{{ $jenis_mata_pelajaran->jenis }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="modal-import" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('admin.mata_pelajaran.import') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Data <span class="text-primary"> Mata
                        Pelajaran</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="data_mata_pelajaran">File <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="data_mata_pelajaran" name="data_mata_pelajaran"
                            accept=".xlsx, .xls">
                        <label class="custom-file-label" for="data_mata_pelajaran">Pilih File</label>
                    </div>
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
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>`
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-edit" class="modal-content" action="" method="post">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary"> Mata Pelajaran</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jenis">Jenis <span class="text-danger">*</span></label>
                    <input type="text" required class="form-control @error('jenis') is-invalid @enderror" id="jenis"
                        name="jenis" value="">
                    @error('jenis')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="kode">Kode <span class="text-danger">*</span></label>
                    <input type="text" required class="form-control @error('kode') is-invalid @enderror" id="kode"
                        name="kode" value="">
                    @error('kode')
                    <div class="invalid-feedback">
                        {{ $message}}
                    </div>
                    @enderror
                </div>
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
                    <label for="urutan">Urutan <span class="text-danger">*</span></label>
                    <select class="form-control @error('urutan') is-invalid @enderror" autocomplete="off" name="urutan"
                        required>
                        <option value="" id="urutan"></option>
                        @for($i = 1; $i <= count($data_mata_pelajaran); $i++) <option value={{ $i }}>{{ $i }}</option>
                            @endfor
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
        <form id="form-delete" class="modal-content" action="" method="get">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus <span class="text-primary"> Mata
                        Pelajaran</span> ?</h5>
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