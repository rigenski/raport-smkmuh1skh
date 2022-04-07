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
                @if($filter->all() && count($siswa))
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
        @if(!$filter->tahun_pelajaran)
        <div class="alert alert-danger">
            * FILTER <b>DATA RAPORT</b> TERLEBIH DAHULU
        </div>
        @elseif(!count($siswa))
        <div class="alert alert-warning">
            * DATA NILAI TIDAK ADA
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Tahun Pelajaran</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        @if(count($siswa))
                        @foreach($siswa[0]->siswa->nilai as
                        $data)
                        <th>{{ $data->mata_pelajaran }}</th>
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
                        <td>{{ $data->tahun_pelajaran }}</td>
                        <td>{{ $data->kelas }}</td>
                        <td>{{ $data->siswa->nis }}</td>
                        <td>{{ $data->siswa->nama }}</td>
                        @foreach($data->siswa->nilai as
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

<!-- Modal Print -->
<div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span class="text-primary"> {{
                        $filter->kelas ? $filter->kelas : '' }}</span> - Semester <span class="text-primary"> {{
                        $filter->semester ? $filter->semester
                        : ''
                        }}</span></h5>
                </span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form id="formDelete" action="{{ route('admin.raport.print') }}" method="get">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
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
        <form action="{{ route('admin.raport') }}" method="get" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tahun_pelajaran">Tahun Pelajaran</label>
                    <select class="form-control" autocomplete="off" id="tahun_pelajaran" name="tahun_pelajaran">
                        @if($filter->tahun_pelajaran)
                        <option value="{{ $filter->tahun_pelajaran }}">{{ $filter->tahun_pelajaran }}</option>
                        @foreach($tahun_pelajaran as $data)
                        @if($data != $filter->tahun_pelajaran)
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
                <div class="form-group">
                    <label for="angkatan">Angkatan</label>
                    <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select class="form-control" autocomplete="off" id="kelas" name="kelas">
                        @if($filter->kelas)
                        <option value="{{ $filter->kelas }}">{{
                            $filter->kelas
                            }}</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select class="form-control" autocomplete="off" id="semester" name="semester">
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
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script>
    const data_nilai = @json($nilai);

    const elTahunPelajaran = document.getElementById('tahun_pelajaran');
    const elAngkatan = document.getElementById('angkatan');
    const elKelas = document.getElementById('kelas');

    const changeKelas = () => {
        const tahun_pelajaran = elTahunPelajaran.value;
        const angkatan = elAngkatan.value;

        const selected = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }

        const data_kelas_filter = data_nilai.filter(selected);

        const kelas_selected = []; 

        data_kelas_filter.map((data) => {
            if(data.angkatan == angkatan) {
                kelas_selected.push(data.kelas);
            }
        })

        const delete_duplicate = (value, index, self) => {
            return self.indexOf(value) === index;
        }

        const data_kelas_filter2 = kelas_selected.filter(delete_duplicate);

        elKelas.innerHTML = '';

        data_kelas_filter2.map((data) => {
            elKelas.innerHTML += `<option value="${data}">${data}</option>`;
        })
    }

    elTahunPelajaran.addEventListener('change', () => {
        changeKelas();
    })

    elAngkatan.addEventListener('change', () => {
        changeKelas();
    })
    
    window.onload = () => {
        changeKelas();
    }
    
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
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modalFilter">
                    Filter
                </button>
                @if($filter->all() && count($siswa))
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
        @if(!$filter->tahun_pelajaran)
        <div class="alert alert-danger">
            * FILTER <b>DATA RAPORT</b> TERLEBIH DAHULU
        </div>
        @elseif(!count($siswa))
        <div class="alert alert-warning">
            * DATA NILAI TIDAK ADA
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        @if(count($siswa))
                        @foreach($siswa[0]->siswa->nilai as
                        $data)
                        <th>{{ $data->mata_pelajaran }}</th>
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
                        <td>{{ $data->tahun_pelajaran }}</td>
                        <td>{{ $data->kelas }}</td>
                        <td>{{ $data->siswa->nis }}</td>
                        <td>{{ $data->siswa->nama }}</td>
                        @foreach($data->siswa->nilai as
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

<!-- Modal Print -->
<div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span class="text-primary"> {{
                        auth()->user()->guru->wali_kelas->kelas }}</span> - Semester <span class="text-primary"> {{
                        $filter->semester ?
                        $filter->semester :
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