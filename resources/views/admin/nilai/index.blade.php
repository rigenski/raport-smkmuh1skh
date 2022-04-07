@extends('layouts.admin')
@section('nav__item-nilai', 'active')

@section('title', 'Nilai')

@if(auth()->user()->role === 'admin')

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
            * FILTER <b>DATA NILAI</b> TERLEBIH DAHULU
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
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Nilai</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($siswa as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->tahun_pelajaran }}</td>
                        <td>{{ $data->mata_pelajaran }}</td>
                        <td>{{ $data->kelas }}</td>
                        <td>{{ $data->siswa->nis }}</td>
                        <td>{{ $data->siswa->nama }}</td>
                        <td>{{ $data->nilai }}</td>
                        <td>{{ $data->keterangan }}</td>
                        @if(!$data->status || auth()->user()->role == 'admin')
                        <td>
                            <a href="#modalEdit" data-toggle="modal"
                                onclick="$('#modalEdit #formEdit').attr('action', 'nilai/{{$data->id}}/update'); $('#modalEdit #formEdit #nilai').attr('value', '{{$data->nilai}}'); $('#modalEdit #formEdit #keterangan').attr('value', '{{$data->keterangan}}');"
                                class="btn btn-warning m-1">Ubah</a>
                            <a href="#modalDelete" data-toggle="modal"
                                onclick="$('#modalDelete #formDelete').attr('action', 'nilai/{{$data->id}}/destroy')"
                                class="btn btn-danger m-1">Hapus</a>
                        </td>
                        @else
                        <td>
                            <button class="btn btn-warning m-1" disabled>Edit</button>
                            <button class="btn btn-danger m-1" disabled>Hapus</button>
                        </td>
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
                <form action="{{ route('admin.nilai') }}" method="get">
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
                        <label for="mata_pelajaran">Mata Pelajaran</label>
                        <select class="form-control" autocomplete="off" id="mata_pelajaran" name="mata_pelajaran">
                            @if($filter->mata_pelajaran)
                            <option value="{{ $filter->mata_pelajaran }}">{{ $filter->mata_pelajaran }}
                            </option>
                            @endif
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
                <h5 class="modal-title" id="staticBackdropLabel">Edit Nilai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nilai">Nilai <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('nilai') is-invalid @enderror" id="nilai"
                            name="nilai" value="">
                        @error('nilai')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('keterangan') is-invalid @enderror"
                            id="keterangan" name="keterangan" value="">
                        @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
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
                <h5 class="modal-title" id="exampleModalLabel">Yakin menghapus data ?</h5>
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

@section('script')
<script>
    const data_mata_pelajaran = @json($mata_pelajaran);
    const data_nilai = @json($nilai);

    const elTahunPelajaran = document.getElementById('tahun_pelajaran');
    const elMataPelajaran = document.getElementById('mata_pelajaran');
    const elKelas = document.getElementById('kelas');

    const changeKelas = () => {
        const tahun_pelajaran = elTahunPelajaran.value;

        const selected = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }

        const data_kelas_filter = data_nilai.filter(selected);

        const kelas_selected = []; 

        data_kelas_filter.map((data) => {
            kelas_selected.push(data.kelas);
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

    const changeMataPelajaran = () => {
        const tahun_pelajaran = elTahunPelajaran.value;

        const selected = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }

        const data_mata_pelajaran_filter = data_mata_pelajaran.filter(selected);

        const mata_pelajaran_selected = []; 

        data_mata_pelajaran_filter.map((data) => {
            mata_pelajaran_selected.push(data.nama);
        })

        const delete_duplicate = (value, index, self) => {
            return self.indexOf(value) === index;
        }

        const data_mata_pelajaran_filter2 = mata_pelajaran_selected.filter(delete_duplicate);

        elMataPelajaran.innerHTML = '';

        data_mata_pelajaran_filter2.map((data) => {
            elMataPelajaran.innerHTML += `<option value="${data}">${data}</option>`;
        })
    }

    elTahunPelajaran.addEventListener('change', () => {
        changeMataPelajaran();
        changeKelas();
    })
    
    window.onload = () => {
        changeMataPelajaran();
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
        @if(!$filter->tahun_pelajaran)
        <div class="alert alert-danger">
            * FILTER DATA NILAI TERLEBIH DAHULU
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
                        <th scope="col">Mata Pelajaran</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Nilai</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; ?>
                    @foreach($siswa as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->tahun_pelajaran }}</td>
                        <td>{{ $data->mata_pelajaran }}</td>
                        <td>{{ $data->kelas }}</td>
                        <td>{{ $data->siswa->nis }}</td>
                        <td>{{ $data->siswa->nama }}</td>
                        <td>{{ $data->nilai }}</td>
                        <td>{{ $data->keterangan }}</td>
                        @if(!$data->status)
                        <td>
                            <a href="#modalEdit" data-toggle="modal"
                                onclick="$('#modalEdit #formEdit').attr('action', 'nilai/{{$data->id}}/update'); $('#modalEdit #formEdit #nilai').attr('value', '{{$data->nilai}}'); $('#modalEdit #formEdit #keterangan').attr('value', '{{$data->keterangan}}');"
                                class="btn btn-warning m-1">Ubah</a>
                            <a href="#modalDelete" data-toggle="modal"
                                onclick="$('#modalDelete #formDelete').attr('action', 'nilai/{{$data->id}}/destroy')"
                                class="btn btn-danger m-1">Hapus</a>
                        </td>
                        @else
                        <td>
                            <button class="btn btn-warning m-1" disabled>Ubah</button>
                            <button class="btn btn-danger m-1" disabled>Hapus</button>
                        </td>
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

<!-- Modal Import -->
<div class="modal fade" id="modalImport" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Excel Data Nilai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.nilai.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nama">File</label>
                        <input type="file" class="form-control" required id="excel" name="data_nilai"
                            accept=".xlsx, .xls">
                        <div class="text-small text-danger mt-2">
                            * Mohon masukkan data dengan benar sebelum dikirim
                        </div>
                        <a href="{{ route('admin.nilai.export_format') }}" class="btn btn-warning mt-4">Unduh
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
                <form action="{{ route('admin.nilai') }}" method="get">
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
                        <label for="mata_pelajaran">Mata Pelajaran</label>
                        <select class="form-control" autocomplete="off" id="mata_pelajaran" name="mata_pelajaran">
                            @if($filter->mata_pelajaran)
                            <option value="{{ $filter->mata_pelajaran }}">{{
                                $filter->mata_pelajaran
                                }}</option>
                            @endif
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
                <h5 class="modal-title" id="staticBackdropLabel">Edit Nilai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit" action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="nilai">Nilai <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('nilai') is-invalid @enderror" id="nilai"
                            name="nilai" value="">
                        @error('nilai')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control @error('keterangan') is-invalid @enderror"
                            id="keterangan" name="keterangan" value="">
                        @error('keterangan')
                        <div class="invalid-feedback">
                            {{ $message}}
                        </div>
                        @enderror
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
                <h5 class="modal-title" id="exampleModalLabel">Yakin menghapus data ?</h5>
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

@section('script')
<script>
    const data_mata_pelajaran = @json($mata_pelajaran); 
    const data_nilai = @json($mata_pelajaran); 

    const elTahunPelajaran = document.getElementById('tahun_pelajaran');
    const elMataPelajaran = document.getElementById('mata_pelajaran');      
    const elKelas = document.getElementById('kelas');

    const changeKelas = () => {
        const tahun_pelajaran = elTahunPelajaran.value;

        const selected = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }

        const data_kelas_filter = data_nilai.filter(selected);

        const kelas_selected = []; 

        data_kelas_filter.map((data) => {
            kelas_selected.push(data.kelas);
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

    const changeMataPelajaran = () => {
        const tahun_pelajaran = elTahunPelajaran.value;

        const selected = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }

        const data_mata_pelajaran_filter = data_mata_pelajaran.filter(selected);

        const mata_pelajaran_selected = []; 

        data_mata_pelajaran_filter.map((data) => {
            mata_pelajaran_selected.push(data.nama);
        })

        const delete_duplicate = (value, index, self) => {
            return self.indexOf(value) === index;
        }

        const data_mata_pelajaran_filter2 = mata_pelajaran_selected.filter(delete_duplicate);

        elMataPelajaran.innerHTML = '';

        data_mata_pelajaran_filter2.map((data) => {
            elMataPelajaran.innerHTML += `<option value="${data}">${data}</option>`;
        })
    }

    elTahunPelajaran.addEventListener('change', () => {
        changeMataPelajaran();
        changeKelas();
    })
    
    window.onload = () => {
        changeMataPelajaran();
        changeKelas();
    }

</script>
@endsection

@endif