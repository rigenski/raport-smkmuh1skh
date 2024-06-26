@extends('layouts.admin')
@section('nav_item-raport', 'active')

@section('title', 'Raport')

@if (auth()->user()->role == 'admin')
    @section('content')
        <div class="card mb-4">
            <div class="card-header row">
                <div class="col-12 col-sm-6 p-0 my-1">
                    <div class="d-flex align-items-start">
                        <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modal-filter">
                            Filter
                        </button>
                        @if (($filter->has('tahun_pelajaran') || $filter->has('kelas') || $filter->has('semester')) && count($data_siswa_aktif))
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
                    <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">RAPORT</span>
                        TERLEBIH DAHULU</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40px;">No</th>
                                <th scope="col">NIS</th>
                                <th scope="col">Nama</th>
                                <?php $data_nama_mata_pelajaran = []; ?>
                                @foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran)
                                    <?php array_push($data_nama_mata_pelajaran, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                                @endforeach
                                <?php sort($data_nama_mata_pelajaran); ?>
                                @foreach ($data_nama_mata_pelajaran as $nama_mata_pelajaran)
                                    <th>{{ $nama_mata_pelajaran[1] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach ($data_siswa_aktif as $siswa_aktif)
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>{{ $siswa_aktif->siswa->nis }}</td>
                                    <td>{{ $siswa_aktif->siswa->nama }}</td>
                                    <?php $data_total_nilai = []; ?>
                                    @foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran)
                                        <?php $nilai = 0; ?>
                                        @foreach ($siswa_aktif->nilai->where('semester', $filter->semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                            <?php $nilai = $data_nilai->nilai; ?>
                                        @endforeach
                                        <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                    @endforeach
                                    <?php sort($data_total_nilai); ?>
                                    @foreach ($data_total_nilai as $total_nilai)
                                        <td>{{ $total_nilai[1] }}</td>
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
        <div class="modal fade" id="modal-filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.raport') }}" method="get" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Data Raport </h5>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Print -->
        <div class="modal fade" id="modalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.raport.print') }}" method="get" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span
                                class="text-primary"> {{ $filter->kelas ? $filter->kelas : '' }}</span> - Semester <span
                                class="text-primary">
                                {{ $filter->semester ? $filter->semester : '' }}</span></h5>
                        </span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="tanggal_raport">Tanggal Raport <span class="text-danger">*</span></label>
                        <input type="date" required class="form-control @error('tanggal_raport') is-invalid @enderror"
                            id="tanggal_raport" name="tanggal_raport" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-primary">Cetak</button>
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
                <div class="col-12 col-sm-8 p-0 my-1">
                    <div class="d-flex align-items-start">
                        <button type="button" class="btn btn-info ml-2" data-toggle="modal"
                            data-target="#modal-filter">
                            Filter
                        </button>
                        @if (count($data_siswa_aktif))
                            <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                                data-target="#modalPrint">
                                Print Raport
                            </button>
                            <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                                data-target="#modalPrintPDF">
                                Print Ranking
                            </button>
                            <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                                data-target="#modalPrintExcel">
                                Export Ranking
                            </button>
                        @else
                            <button type="button" class="btn btn-primary ml-2" disabled>
                                Print Raport
                            </button>
                            <button type="button" class="btn btn-primary ml-2" disabled>
                                Print Ranking
                            </button>
                            <button type="button" class="btn btn-primary ml-2" disabled>
                                Export Ranking
                            </button>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-sm-4 p-0 my-1">
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 40px;">No</th>
                                <th scope="col">NIS</th>
                                <th scope="col">Nama</th>
                                <?php $data_nama_mata_pelajaran = []; ?>
                                @foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran)
                                    <?php array_push($data_nama_mata_pelajaran, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                                @endforeach
                                <?php sort($data_nama_mata_pelajaran); ?>
                                @foreach ($data_nama_mata_pelajaran as $nama_mata_pelajaran)
                                    <th>{{ $nama_mata_pelajaran[1] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach ($data_siswa_aktif as $siswa_aktif)
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>{{ $siswa_aktif->siswa->nis }}</td>
                                    <td>{{ $siswa_aktif->siswa->nama }}</td>
                                    <?php $data_total_nilai = []; ?>
                                    @foreach ($data_guru_mata_pelajaran as $guru_mata_pelajaran)
                                        <?php $nilai = 0; ?>
                                        @foreach ($siswa_aktif->nilai->where('semester', $filter->semester ? $filter->semester : $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                            <?php $nilai = $data_nilai->nilai; ?>
                                        @endforeach
                                        <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                    @endforeach
                                    <?php sort($data_total_nilai); ?>
                                    @foreach ($data_total_nilai as $total_nilai)
                                        <td>{{ $total_nilai[1] }}</td>
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
                <form action="{{ route('admin.raport.print') }}" method="get" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport Kelas <span
                                class="text-primary"> {{ $kelas }}</span> - Semester <span class="text-primary">
                                {{ $filter->semester ? $filter->semester : '1' }}
                            </span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="tanggal_raport">Tanggal Raport <span class="text-danger">*</span></label>
                        <input type="date" required class="form-control @error('tanggal_raport') is-invalid @enderror"
                            id="tanggal_raport" name="tanggal_raport" value="">
                    </div>
                    <div class="modal-footer">
                        <form id="form-delete" action="{{ route('admin.raport.print') }}" method="get">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn btn-primary">Cetak</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Filter -->
        <div class="modal fade" id="modal-filter" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Export -->
        <div class="modal fade" id="modalPrintPDF" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="form-print" action="{{ route('admin.ranking.print') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Export Legger Kelas <span
                                class="text-primary"> {{ $filter->kelas ? $filter->kelas : '' }} </span> - Semester <span
                                class="text-primary">{{ $filter->semester ? $filter->semester : '' }}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="tanggal_legger">Tanggal Legger <span class="text-danger">*</span></label>
                        <input type="date" required class="form-control @error('tanggal_legger') is-invalid @enderror"
                            id="tanggal_legger" name="tanggal_legger" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-primary">Cetak</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal Export Excel -->
        <div class="modal fade" id="modalPrintExcel" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" id="form-print" action="{{ route('admin.ranking.export_excel') }}"
                    method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Export Legger Kelas <span
                                class="text-primary"> {{ $filter->kelas ? $filter->kelas : '' }} </span> - Semester <span
                                class="text-primary">{{ $filter->semester ? $filter->semester : '' }}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="tanggal_legger">Tanggal Legger <span class="text-danger">*</span></label>
                        <input type="date" required class="form-control @error('tanggal_legger') is-invalid @enderror"
                            id="tanggal_legger" name="tanggal_legger" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-primary">Cetak</button>
                    </div>
                </form>
            </div>
        </div>

    @endsection
@endif
