@extends('layouts.admin')
@section('nav__item-raport', 'active')

@section('title', 'Raport')

@if(auth()->user()->role == 'admin')
@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modalFilter">
                    Filter
                </button>
                @if($filter->all())
                <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalPrint">
                    Print PDF
                </button>
                @else
                <button type="button" class="btn btn-primary ml-2" disabled>
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
        @if(!$filter->kelas)
        <div class="alert alert-danger">
            * FILTER <b>DATA RAPORT</b> TERLEBIH DAHULU
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        @if($siswa)
                        @foreach($nilai->where('siswa_id', $siswa[0]->id)->where('semester', $filter->semester) as
                        $data)
                        <th scope="col">{{ $data->mapel }}</th>
                        @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody id="list-container">
                    <?php $count = 1; ?>
                    @foreach($siswa as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->nis }}</td>
                        <td>{{ $data->nama }}</td>
                        @foreach($nilai->where('siswa_id', $data->id)->where('semester', $filter->semester) as
                        $data)
                        <td>{{ $data->nilai }}</td>
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
<div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span class="text-primary"> {{
                        $filter->kelas ? $filter->kelas : '' }} - {{ $filter->semester ? 'Semester ' . $filter->semester
                        : ''
                        }}</span></h5>
                </span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form id="formDelete" action="{{ route('admin.raport.print') }}" method="get">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                        <label for="kelas">Kelas</label>
                        <select class="form-control" autocomplete="off" name="kelas">
                            @if($filter->kelas)
                            <option value="{{ $filter->kelas }}">{{ $filter->kelas }}</option>
                            @foreach($kelas as $data)
                            @if($filter->kelas != $data->kelas)
                            <option value="{{ $data->kelas }}">{{ $data->kelas }}</option>
                            @endif
                            @endforeach
                            @else
                            @foreach($kelas as $data)
                            <option value="{{ $data->kelas }}">{{ $data->kelas }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" autocomplete="off" name="semester">
                            @if($filter->semester)
                            <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                            @foreach($semester as $data)
                            @if($data != $filter->semester)
                            <option value="{{ $data }}">{{ $data }}</option>
                            @endif
                            @endforeach
                            @else
                            @foreach($semester as $data)
                            <option value="{{ $data }}">{{ $data }}</option>
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

@else

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalPrint">
                    Print
                </button>
                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modalFilter">
                    Filter
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
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        @if($siswa)
                        @if($filter->semester)
                        @foreach($siswa[0]->nilai->where('semester', $filter->semester) as $nilai)
                        <th scope="col">{{ $nilai->mapel->nama }}</th>
                        @endforeach
                        @else
                        @foreach($siswa[0]->nilai->where('semester', 1) as $nilai)
                        <th scope="col">{{ $nilai->mapel->nama }}</th>
                        @endforeach
                        @endif
                        @endif
                    </tr>
                </thead>
                <tbody id="list-container">
                    <?php $count = 1; ?>
                    @foreach($siswa as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->nis }}</td>
                        <td>{{ $data->nama }}</td>
                        @if($filter->semester)
                        @foreach($data->nilai->where('semester', $filter->semester) as $nilai)
                        <td>{{ $nilai->nilai }}</td>
                        @endforeach
                        @else
                        @foreach($data->nilai->where('semester', 1) as $nilai)
                        <td>{{ $nilai->nilai }}</td>
                        @endforeach
                        @endif
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
<div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span class="text-primary"> {{
                        auth()->user()->guru->wali_kelas->kelas }} - Semester {{ $filter->semester ? $filter->semester :
                        '1' }}
                    </span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form id="formDelete" action="{{ route('admin.raport.print') }}" method="get">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                            @if($filter->semester)
                            <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                            @foreach($semester as $data)
                            @if($data != $filter->semester)
                            <option value="{{ $data }}">{{ $data }}</option>
                            @endif
                            @endforeach
                            @else
                            @foreach($semester as $data)
                            <option value="{{ $data }}">{{ $data }}</option>
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