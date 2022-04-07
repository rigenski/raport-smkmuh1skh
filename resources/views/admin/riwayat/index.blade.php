@extends('layouts.admin')
@section('nav__item-riwayat', 'active')

@section('title', 'Riwayat')

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalFilter">
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
        @if(!$filter->tahun_pelajaran)
        <div class="alert alert-danger">
            * FILTER <b>DATA KELAS</b> TERLEBIH DAHULU
        </div>
        @elseif(!count($kelas))
        <div class="alert alert-warning">
            * DATA KELAS TIDAK ADA
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kelas</th>
                        @foreach($semester as $data)
                        <th scope="col">{{ $data }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="list-container">

                    <?php $count = 1; ?>
                    @foreach($kelas as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->kelas }}</td>
                        @foreach($semester as $smt)
                        <?php $status = 0 ?>
                        @foreach($nilai->where('semester', $smt)->where('kelas',
                        $data->kelas)->unique('kelas')->values() as
                        $value)
                        @if($value->status == 1)
                        <?php $status = 1 ?>
                        @endif
                        @endforeach
                        @if($status == 1)
                        <td class="bg-success text-light font-weight-bold">Sudah</td>
                        @else
                        <td class="bg-danger text-light font-weight-bold">Belum</td>
                        @endif
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
<div class="modal fade" id="modalFilter" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.riwayat') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Data Wali Kelas</h5>
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
@endsection