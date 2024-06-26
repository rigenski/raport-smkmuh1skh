@extends('layouts.admin')
@section('nav_item-nilai', 'active')

@section('title', 'Nilai')

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
                @if (
                    $filter->has('tahun_pelajaran') ||
                        $filter->has('kelas') ||
                        $filter->has('mata_pelajaran') ||
                        $filter->has('semester'))
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
                                    <td class="h6">Mata Pelajaran</td>
                                    <td class="h6 px-2">:</td>
                                    <td class="h6 text-primary"><b>{{ $filter->mata_pelajaran }}</b></td>
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
                    <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">NILAI</span>
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
                                    <td>{{ $siswa_aktif->nilai ? $siswa_aktif->nilai : '0' }}</td>
                                    <td>{{ $siswa_aktif->keterangan_nilai ? $siswa_aktif->keterangan_nilai : '-' }}</td>
                                    @if ($siswa_aktif->nilai_id !== null)
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'nilai/{{ $siswa_aktif->nilai_id }}/update'); $('#modal-edit #form-edit #nilai').attr('value', '{{ $siswa_aktif->nilai }}'); $('#modal-edit #form-edit #keterangan').attr('value', '{{ $siswa_aktif->keterangan_nilai }}');"
                                                class="btn btn-warning m-1">Ubah</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'nilai/{{ $siswa_aktif->siswa_aktif_id }}/{{ $mata_pelajaran->id }}/store');"
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
                <form class="modal-content" action="{{ route('admin.nilai') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span class="text-primary">Nilai</span>
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
                            <label for="mata_pelajaran">Mata Pelajaran</label>
                            <select class="form-control" autocomplete="off" id="mata_pelajaran"
                                name="mata_pelajaran"></select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="kelas">Kelas</label>
                            <select class="form-control" autocomplete="off" id="kelas" name="kelas"></select>
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
                        <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary">Nilai</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nilai">Nilai <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control @error('nilai') is-invalid @enderror"
                                id="nilai" name="nilai" value="">
                            @error('nilai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" value="">
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
            const data_mata_pelajaran = @json($data_mata_pelajaran);

            const elTahunPelajaran = document.getElementById('tahun_pelajaran');
            const elMataPelajaran = document.getElementById('mata_pelajaran');
            const elKelas = document.getElementById('kelas');

            const changeKelas = () => {
                const tahun_pelajaran = elTahunPelajaran.value;

                const selected = (data) => {
                    return data.tahun_pelajaran == tahun_pelajaran;
                }

                const data_kelas_filter = data_mata_pelajaran.filter(selected);

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
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-filter">
                            Filter
                        </button>
                        <button type="button" class="btn btn-warning ml-2" data-toggle="modal"
                            data-target="#modalFormatImport">
                            Format
                            Import</button>
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
                @if ($filter->has('kelas') || $filter->has('mata_pelajaran') || $filter->has('semester'))
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
                                    <td class="h6 text-primary"><b>{{ $filter->kelas }}</b></td>
                                </tr>
                                <tr>
                                    <td class="h6">Mata Pelajaran</td>
                                    <td class="h6 px-2">:</td>
                                    <td class="h6 text-primary"><b>{{ $filter->mata_pelajaran }}</b></td>
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
                    <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">NILAI</span>
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
                                    <td>{{ $siswa_aktif->nilai ? $siswa_aktif->nilai : '0' }}</td>
                                    <td>{{ $siswa_aktif->keterangan_nilai ? $siswa_aktif->keterangan_nilai : '-' }}</td>
                                    @if ($siswa_aktif->nilai !== null)
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'nilai/{{ $siswa_aktif->nilai_id }}/update'); $('#modal-edit #form-edit #nilai').attr('value', '{{ $siswa_aktif->nilai }}'); $('#modal-edit #form-edit #keterangan').attr('value', '{{ $siswa_aktif->keterangan_nilai }}');"
                                                class="btn btn-warning m-1">Ubah</a>
                                        </td>
                                    @else
                                        <td>
                                            <a href="#modal-edit" data-toggle="modal"
                                                onclick="$('#modal-edit #form-edit').attr('action', 'nilai/{{ $siswa_aktif->siswa_aktif_id }}/{{ $siswa_aktif->mapel_id }}/store');"
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
                <form class="modal-content" action="{{ route('admin.nilai') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span
                                class="text-primary">Nilai</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="mata_pelajaran">Mata Pelajaran</label>
                            <select class="form-control" autocomplete="off" id="mata_pelajaran"
                                name="mata_pelajaran"></select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="kelas">Kelas</label>
                            <select class="form-control" autocomplete="off" id="kelas" name="kelas"></select>
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Import -->
        <div class="modal fade" id="modal-import" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('admin.nilai.import') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Import Data <span
                                class="text-primary">Nilai</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nama">File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" required id="excel" name="data_nilai"
                                accept=".xlsx, .xls">
                            <div class="text-small text-danger mt-2">
                                * Mohon masukkan data dengan benar sebelum dikirim
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Format Import -->
        <div class="modal fade" id="modalFormatImport" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="{{ route('admin.nilai.export_format') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Format Import <span
                                class="text-primary">Nilai</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="guru_mata_pelajaran">Kelas - Mata Pelajaran</label>
                            <select class="form-control" autocomplete="off" id="guru_mata_pelajaran"
                                name="guru_mata_pelajaran">
                                @foreach (auth()->user()->guru->guru_mata_pelajaran as $data)
                                    <option value="{{ $data->id }}">{{ $data->kelas }} -
                                        {{ $data->mata_pelajaran->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-warning">Unduh Format Export</button>
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
                        <h5 class="modal-title" id="staticBackdropLabel">Ubah <span class="text-primary">Nilai</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="nilai">Nilai <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control @error('nilai') is-invalid @enderror"
                                id="nilai" name="nilai" value="">
                            @error('nilai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control @error('keterangan') is-invalid @enderror"
                                id="keterangan" name="keterangan" value="">
                            @error('keterangan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
            const data_mata_pelajaran = @json($data_mata_pelajaran);
            const tahun_pelajaran = @json($setting->tahun_pelajaran);

            const elMataPelajaran = document.getElementById('mata_pelajaran');
            const elKelas = document.getElementById('kelas');

            const changeKelas = () => {
                const selected = (data) => {
                    return data.tahun_pelajaran == tahun_pelajaran;
                }

                const data_kelas_filter = data_mata_pelajaran.filter(selected);

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

            window.onload = () => {
                changeMataPelajaran();
                changeKelas();
            }
        </script>
    @endsection

@endif
