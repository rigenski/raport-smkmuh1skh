@extends('layouts.admin')
@section('nav_item-ekskul', 'active')

@section('title', 'Ekstrakurikuler')

@if (auth()->user()->role === 'admin')

    @section('content')
        <div class="card mb-4">
            <div class="card-header row">
                <div class="col-12 col-sm-6 p-0 my-1">
                    <div class="d-flex align-items-start">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-filter">
                            Filter
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
                @if ($filter->has('tahun_pelajaran') || $filter->has('kelas') || $filter->has('semester'))
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
                                <tr>
                                    <td class="h6">Kelas</td>
                                    <td class="h6 px-2">:</td>
                                    <td class="h6 text-primary"><b>{{ $filter->kelas }}</b></td>
                                </tr>
                                <tr>
                                    <td class="h6">Semester</td>
                                    <td class="h6 px-2">:</td>
                                    <td class="h6 text-primary"><b>{{ $filter->semester }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">EKSTRAKURIKULER</span>
                        TERLEBIH DAHULU</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40px;">No</th>
                                <th scope="col">NIS</th>
                                <th scope="col">Nama Siswa</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach ($data_siswa_aktif as $siswa_aktif)
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>{{ $siswa_aktif->nis }}</td>
                                    <td>{{ $siswa_aktif->nama_siswa }}</td>
                                    <td>{{ $siswa_aktif->nama_ekskul ? $siswa_aktif->nama_ekskul : '-' }}</td>
                                    <td>{{ $siswa_aktif->keterangan_ekskul ? $siswa_aktif->keterangan_ekskul : '-' }}</td>
                                    @if ($siswa_aktif->ekskul_id)
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'ekskul/{{ $siswa_aktif->ekskul_id }}/update'); $('#modal-edit #form-edit #ekskul').attr('value', '{{ $siswa_aktif->nama_ekskul }}'); $('#modal-edit #form-edit #keterangan').attr('value', '{{ $siswa_aktif->keterangan_ekskul }}');"
                                                class="btn btn-warning m-1">Ubah</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'ekskul/{{ $siswa_aktif->siswa_aktif_id }}/store');"
                                                class="btn btn-warning m-1">Ubah</a>
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
        <div class="modal fade" id="modal-filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('admin.ekskul') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span class="text-primary">
                                Ekskul</span>
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
                                    <option value="{{ $setting->tahun_pelajaran }}">{{ $setting->tahun_pelajaran }}
                                    </option>
                                    @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                        @if ($setting->tahun_pelajaran !== $tahun_pelajaran)
                                            <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="angkatan">Angkatan</label>
                            <select class="form-control" autocomplete="off" id="angkatan" name="angkatan">
                                @foreach ($data_angkatan as $angkatan)
                                    <option value="{{ $angkatan->angkatan }}">{{ $angkatan->angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="kelas">Kelas</label>
                            <select class="form-control" autocomplete="off" id="kelas" name="kelas">
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="semester">Semester</label>
                            <select class="form-control" autocomplete="off" name="semester">
                                @if ($filter->has('semester'))
                                    <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                                    @foreach ($data_semester as $semester)
                                        @if ($semester != $filter->semester)
                                            <option value="{{ $semester }}">{{ $semester }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($data_semester as $semester)
                                        <option value="{{ $semester }}">{{ $semester }}</option>
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

        <!-- Modal Edit -->
        <div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form-edit" class="modal-content" action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary"> Ekskul</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="ekskul">Ekskul</label>
                            <input type="text" class="form-control" id="ekskul" name="ekskul" value="">
                        </div>
                        <div class="form-group mb-2">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            const data_siswa = @json($data_siswa);

            const elTahunPelajaran = document.getElementById('tahun_pelajaran');
            const elAngkatan = document.getElementById('angkatan');
            const elKelas = document.getElementById('kelas');

            const changeKelas = () => {
                const angkatan = elAngkatan.value;
                const tahun_pelajaran = elTahunPelajaran.value;

                const filter_tahun_pelajaran = (data) => {
                    return data.tahun_pelajaran == tahun_pelajaran;
                }

                const data_kelas_filter = data_siswa.filter(filter_tahun_pelajaran);

                const filter_angkatan = (data) => {
                    return data.angkatan == angkatan;
                }

                const data_kelas_filter2 = data_kelas_filter.filter(filter_angkatan);

                const kelas_selected = [];

                data_kelas_filter2.map((data) => {
                    kelas_selected.push(data.kelas);
                })

                const delete_duplicate = (value, index, self) => {
                    return self.indexOf(value) === index;
                }

                const data_kelas_filter3 = kelas_selected.filter(delete_duplicate);

                let kelas_option = '';

                data_kelas_filter3.map((data, i) => {
                    kelas_option += `<option value="${data}">${data}</option>`;
                })

                elKelas.innerHTML = kelas_option;
            }

            elTahunPelajaran.addEventListener('change', () => {
                changeKelas();
            })

            elAngkatan.addEventListener('change', () => {
                changeKelas();
            })

            window.onload = () => {
                changeKelas();
            };
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
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-filter">
                            Filter
                        </button>
                        <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                            data-target="#modal-import">
                            Import
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
                            <tr>
                                <td class="h6">Kelas</td>
                                <td class="h6 px-2">:</td>
                                <td class="h6 text-primary"><b>{{ $kelas }}</b></td>
                            </tr>
                            <tr>
                                <td class="h6">Semester</td>
                                <td class="h6 px-2">:</td>
                                <td class="h6 text-primary"><b>{{ $filter->semester ? $filter->semester : $semester }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40px;">No</th>
                                <th scope="col">NIS</th>
                                <th scope="col">Nama Siswa</th>
                                <th scope="col">Ekskul</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach ($data_siswa_aktif as $siswa_aktif)
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>{{ $siswa_aktif->nis }}</td>
                                    <td>{{ $siswa_aktif->nama_siswa }}</td>
                                    <td>{{ $siswa_aktif->nama_ekskul ? $siswa_aktif->nama_ekskul : '-' }}</td>
                                    <td>{{ $siswa_aktif->keterangan_ekskul ? $siswa_aktif->keterangan_ekskul : '-' }}</td>
                                    @if ($siswa_aktif->ekskul_id)
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'ekskul/{{ $siswa_aktif->ekskul_id }}/update'); $('#modal-edit #form-edit #ekskul').attr('value', '{{ $siswa_aktif->nama_ekskul }}'); $('#modal-edit #form-edit #keterangan').attr('value', '{{ $siswa_aktif->keterangan_ekskul }}');"
                                                class="btn btn-warning m-1">Ubah</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'ekskul/{{ $siswa_aktif->siswa_aktif_id }}/store');"
                                                class="btn btn-warning m-1">Ubah</a>
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
        <div class="modal fade" id="modal-filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('admin.ekskul') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span class="text-primary">
                                Ekskul</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="semester">Semester</label>
                            <select class="form-control" autocomplete="off" name="semester">
                                @if ($filter->has('semester'))
                                    <option value="{{ $filter->semester }}">{{ $filter->semester }}</option>
                                    @foreach ($data_semester as $semester)
                                        @if ($semester != $filter->semester)
                                            <option value="{{ $semester }}">{{ $semester }}</option>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach ($data_semester as $semester)
                                        <option value="{{ $semester }}">{{ $semester }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Import -->
        <div class="modal fade" id="modal-import" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('admin.ekskul.import') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Import <span class="text-primary"> Ekskul</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nama">File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" required id="excel" name="data_ekskul"
                                accept=".xlsx, .xls">
                            <div class="text-small text-danger mt-2">
                                * Mohon masukkan data dengan benar sebelum dikirim
                            </div>
                            <a href="{{ route('admin.ekskul.export_format') }}" class="btn btn-warning mt-4">Unduh
                                Format
                                Import</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Edit -->
        <div class="modal fade" id="modal-edit" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form-edit" class="modal-content" action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary"> Ekskul</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="ekskul">Ekskul</label>
                            <input type="text" class="form-control" id="ekskul" name="ekskul" value="">
                        </div>
                        <div class="form-group mb-2">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    @endsection

@endif
