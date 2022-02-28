@extends('layouts.admin')
@section('nav__item-ranking', 'active')

@section('title', 'Ranking')

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalFilter">
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
        @if(!$filter->tipe)
        <div class="alert alert-danger">
            * FILTER <b>DATA RANKING</b> TERLEBIH DAHULU
        </div>
        @endif
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Total Nilai</th>
                    </tr>
                </thead>
                <tbody id="list-container">
                    {{--
                    <?php $count = 1; ?>
                    @foreach($siswa as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->nis }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data_total_nilai[$count - 1] }}</td>
                    </tr>
                    <?php $count++; ?>
                    @endforeach --}}
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
                <h5 class="modal-title" id="staticBackdropLabel">Filter Data Ranking</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.ranking') }}" method="get">
                    <div class="form-group">
                        <label for="tipe">Tipe</label>
                        <select class="form-control" autocomplete="off" id="tipe-ranking" name="tipe">
                            <option value="angkatan">Per Angkatan</option>
                            <option value="angkatan-jurusan">Per Angkatan Per Jurusan</option>
                            <option value="kelas">Per Kelas</option>
                        </select>
                    </div>
                    <div id="form-container">
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select class="form-control" autocomplete="off" name="semester">
                            @foreach($semester as $data)
                            <option value="{{ $data }}">{{ $data }}</option>
                            @endforeach
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

@section('script')
<script>
    // data from laravel
    const kelas = <?= $kelas ?>;
    const jurusan = <?= $jurusan ?>;

    const elTipeRanking = document.getElementById('tipe-ranking');
    const elFormContainer = document.getElementById('form-container');

    const changeForm = () => {
        if(elTipeRanking.value == 'angkatan') {
            elFormContainer.innerHTML = 
            `<div class="form-group">
                <label for="angkatan">Angkatan</label>
                <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                    <option value="x">X</option>
                    <option value="xi">XI</option>
                    <option value="xii">XII</option>
                </select>
            </div>`;
        } else if(elTipeRanking.value == 'angkatan-jurusan') {
            let jurusan_option = '';

            jurusan.map((x, i) => {
                jurusan_option += `<option value="${x.jurusan}">${x.jurusan}</option>`;
            })

            elFormContainer.innerHTML = 
            `<div class="form-group">
                <label for="angkatan">Angkatan</label>
                <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                    <option value="x">X</option>
                    <option value="xi">XI</option>
                    <option value="xii">XII</option>
                </select>
            </div>
            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <select class="form-control" autocomplete="off" id="jurusan" name="jurusan">
                    ${jurusan_option}
                </select>
            </div>`;
        } else if(elTipeRanking.value == 'kelas') {
            let kelas_option = '';

            kelas.map((x, i) => {
                kelas_option += `<option value="${x.kelas + ' ' + x.jurusan}">${x.kelas + ' ' + x.jurusan}</option>`;
            })

            
            elFormContainer.innerHTML = 
            `<div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" autocomplete="off" id="kelas" name="kelas">
                    ${kelas_option}
                </select>
            </div>`;
        }
    }

    elTipeRanking.addEventListener('change', () => {
        changeForm();
    })

    

    // get data

    const siswa = <?= $siswa ?>;

    const elListContainer = document.getElementById('list-container');

    const changeList = () => {
        let siswa_item = '';

        siswa.map((x, i) => {
            siswa_item += `<tr><td>${i + 1}</td><td>${x.nis}</td><td>${x.nama}</td><td>${x.total_nilai}</td></tr>`;
        })

        elListContainer.innerHTML = `${siswa_item}`;
    }

    // start

    window.onload = () => {
        changeForm();
        changeList();
    };
</script>

@endsection