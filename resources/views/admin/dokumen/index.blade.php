@extends('layouts.admin')
@section('nav_item-dokumen', 'active')

@section('title', 'Dokumen')

@section('content')
    <div class="card mb-4">
        <div class="card-header row">
            <div class="col-12 col-sm-6 p-0 my-1">
                <div class="d-flex align-items-start">
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-filter">
                        Filter
                    </button>
                    <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modal-create">
                        Tambah
                    </button>
                </div>
            </div>
            <div class="col-12 col-sm-6 p-0 my-1">
                <div class="d-flex align-items-end flex-column">
                    <div>
                        @if (session('success'))
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
            @if ($filter->has('tahun_pelajaran'))
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
                        </tbody>
                    </table>
                </div>
            @else
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
                                <td class="h6 text-primary"><b>{{ $setting->tahun_pelajaran }}</b></td>
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
                            <th scope="col">Nama</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1; ?>
                        @foreach ($data_dokumen as $dokumen)
                            <tr>
                                <td>
                                    <?= $count ?>
                                </td>
                                <td>{{ $dokumen->nama }}</td>
                                <td>
                                    <a href="/dokumen/{{ $dokumen->dokumen }}" class="btn btn-primary m-1">Unduh</a>
                                    @if (auth()->user()->role == 'admin')
                                        <a href="#modal-edit" data-toggle="modal"
                                            onclick="$('#modal-edit #form-edit').attr('action', 'dokumen/{{ $dokumen->id }}/update'); $('#modal-edit #form-edit #nama').attr('value', '{{ $dokumen->nama }}');"
                                            class="btn btn-warning m-1">Ubah</a>
                                        <a href="#modal-delete" data-toggle="modal"
                                            onclick="$('#modal-delete #form-delete').attr('action', 'dokumen/{{ $dokumen->id }}/destroy')"
                                            class="btn btn-danger m-1">Hapus</a>
                                    @endif
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
            <form action="{{ route('admin.dokumen') }}" method="get" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span class="text-primary"> Dokumen</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="tahun_pelajaran">Tahun Pelajaran</label>
                        <select class="form-control" autocomplete="off" id="tahun_pelajaran" name="tahun_pelajaran">
                            @if ($filter->has('tahun_pelajaran'))
                                <option value="{{ $filter->tahun_pelajaran }}">{{ $filter->tahun_pelajaran }}</option>
                                @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                    @if ($filter->tahun_pelajaran !== $tahun_pelajaran)
                                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                    @endif
                                @endforeach
                            @else
                                <option value="{{ $setting->tahun_pelajaran }}">{{ $setting->tahun_pelajaran }}</option>
                                @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                    @if ($setting->tahun_pelajaran !== $tahun_pelajaran)
                                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                    @endif
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

    <!-- Modal Create -->
    <div class="modal fade" id="modal-create" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-create" class="modal-content" action="{{ route('admin.dokumen.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Tambah <span class="text-primary"> Dokumen</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="tahun_pelajaran">Tahun Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-control" autocomplete="off" id="tahun_pelajaran" name="tahun_pelajaran">
                            @if ($filter->has('tahun_pelajaran'))
                                <option value="{{ $filter->tahun_pelajaran }}">{{ $filter->tahun_pelajaran }}</option>
                                @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                    @if ($filter->tahun_pelajaran !== $tahun_pelajaran)
                                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                    @endif
                                @endforeach
                            @else
                                <option value="{{ $setting->tahun_pelajaran }}">{{ $setting->tahun_pelajaran }}</option>
                                @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                    @if ($setting->tahun_pelajaran !== $tahun_pelajaran)
                                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('nama') is-invalid @enderror"
                            id="nama" name="nama" value="">
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <label for="dokumen">Dokumen <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="dokumen" name="dokumen">
                            <label class="custom-file-label" for="dokumen">Pilih File</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-edit" class="modal-content" action="" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary"> Dokumen</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="nama">Nama <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('nama') is-invalid @enderror"
                            id="nama" name="nama" value="">
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group mb-2">
                        <label for="dokumen">Dokumen</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="dokumen" name="dokumen">
                            <label class="custom-file-label" for="dokumen">Pilih File</label>
                        </div>
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
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Dokumen ?</h5>
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
