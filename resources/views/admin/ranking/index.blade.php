@extends('layouts.admin')
@section('nav__item-ranking', 'active')

@section('title', 'Ranking')

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
            * FILTER <b>DATA RANKING</b> TERLEBIH DAHULU
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
                        <th scope="col">Jumlah Nilai</th>
                        <th scope="col">Rata Nilai</th>
                    </tr>
                </thead>
                <tbody id="list-container">
                    <?php $count = 1; ?>
                    @foreach($siswa as $data)
                    <?php $jmlh_nilai = 0; ?>
                    @foreach($data->siswa->nilai as $nilai)
                    <?php $jmlh_nilai += $nilai->nilai ?>
                    @endforeach
                    <?php $rata_nilai = $jmlh_nilai / count($data->siswa->nilai); ?>
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->tahun_pelajaran }}</td>
                        <td>{{ $data->kelas }}</td>
                        <td>{{ $data->siswa->nis }}</td>
                        <td>{{ $data->siswa->nama }}</td>
                        <td>{{ $jmlh_nilai }}</td>
                        <td>{{ $rata_nilai }}</td>
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
                    <div id="form-container-ranking">
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

<!-- Modal Export -->
<div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                @if($filter->tipe == 'angkatan')
                <h5 class="modal-title" id="exampleModalLabel">Yakin Export Legger Angkatan <span class="text-primary">
                        {{
                        $filter->angkatan ? strtoupper($filter->angkatan) : '' }} - {{ $filter->semester ? 'Semester ' .
                        $filter->semester
                        : ''
                        }}</span></h5>
                </span></h5>
                @elseif($filter->tipe == 'angkatan-jurusan')
                <h5 class="modal-title" id="exampleModalLabel">Yakin Export Legger Angkatan <span class="text-primary">
                        {{ $filter->angkatan ? $filter->angkatan : '' }}</span> - Jurusan <span class="text-primary">{{
                        $filter->jurusan ? $filter->jurusan : '' }}</span> - Semester <span class="text-primary">{{
                        $filter->semester ? $filter->semester
                        : ''
                        }}</span></h5>
                </span></h5>
                @elseif($filter->tipe == 'kelas')
                <h5 class="modal-title" id="exampleModalLabel">Yakin Export Legger Kelas <span class="text-primary"> {{
                        $filter->kelas ? $filter->kelas : '' }} </span> - Semester <span class="text-primary">{{
                        $filter->semester ? $filter->semester : '' }}</span>
                </h5>
                </span></h5>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <form id="formDelete" action="{{ route('admin.ranking.print') }}" method="get">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary">Cetak</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    const data_nilai = @json($nilai);

    const elTipeRanking = document.getElementById('tipe-ranking');
    const elTahunPelajaran = document.getElementById('tahun_pelajaran');
    const elFormContainer = document.getElementById('form-container-ranking');

    const changeJurusan = () => {
            const elAngkatan = document.getElementById('angkatan');

            const data_jurusan = data_nilai;
            const tahun_pelajaran = elTahunPelajaran.value;
            const angkatan = elAngkatan.value;

            const selected = (data) => {
                return data.tahun_pelajaran == tahun_pelajaran;
            }

            const data_angkatan_filter = data_jurusan.filter(selected);

            const angkatan_selected = []; 

            data_angkatan_filter.map((data) => {
                if(data.angkatan == angkatan) {
                    angkatan_selected.push(data.jurusan);
                }
            })

            const delete_duplicate = (value, index, self) => {
                return self.indexOf(value) === index;
            }

            const data_jurusan_filter = angkatan_selected.filter(delete_duplicate);

            let jurusan_option = '';

            data_jurusan_filter.map((data, i) => {
                jurusan_option += `<option value="${data}">${data}</option>`;
            })

            elFormContainer.innerHTML = 
            `
            <div class="form-group">
                            <label for="angkatan">Angkatan</label>
                            <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                        </div>
            <div class="form-group">
                <label for="jurusan">Jurusan</label>
                <select class="form-control" autocomplete="off" id="jurusan" name="jurusan">
                    ${jurusan_option}
                </select>
            </div>`;

            elAngkatan.addEventListener('change', () => {
              changeJurusan();
            })

    }

    const changeKelas = () => {
        const data_kelas = data_nilai;
        const tahun_pelajaran = elTahunPelajaran.value;

        const selected = (data) => {
            return data.tahun_pelajaran == tahun_pelajaran;
        }

            const data_kelas_filter = data_kelas.filter(selected);

            const kelas_selected = []; 

            data_kelas_filter.map((data) => {
                    kelas_selected.push(data.kelas);
            })

            const delete_duplicate = (value, index, self) => {
                return self.indexOf(value) === index;
            }

            const data_kelas_filter2 = kelas_selected.filter(delete_duplicate);

            let kelas_option = '';

            data_kelas_filter2.map((data, i) => {
                kelas_option += `<option value="${data}">${data}</option>`;
            })

            elFormContainer.innerHTML = 
            `<div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" autocomplete="off" id="kelas" name="kelas">
                    ${kelas_option}
                </select>
            </div>`;
    }

    const changeAngkatan = () => {
        elFormContainer.innerHTML = `<div class="form-group">
                            <label for="angkatan">Angkatan</label>
                            <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                        </div>`;
    }

    const changeForm = () => {
        if(elTipeRanking.value == 'angkatan') {
            changeAngkatan();
        } else if(elTipeRanking.value == 'angkatan-jurusan') {
            changeJurusan();
        } else if(elTipeRanking.value == 'kelas') {
            changeKelas()
        }
    }

    elTipeRanking.addEventListener('change', () => {
        changeForm();
    })

    elTahunPelajaran.addEventListener('change', () => {
        if(elTipeRanking.value == 'angkatan') {

        } else if (elTipeRanking.value == 'angkatan-jurusan') {
            changeJurusan();
        } else if(elTipeRanking.value == 'kelas') {
            changeKelas();
        }
    })

    window.onload = () => {
        changeForm();
    };
</script>

@endsection