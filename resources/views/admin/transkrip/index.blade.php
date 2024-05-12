@extends('layouts.admin')
@section('nav_item-transkrip', 'active')

@section('title', 'Transkrip')

@if (auth()->user()->role == 'admin')
    @section('content')
        <div class="card mb-4">
            <div class="card-header row">
                <div class="col-12 col-sm-6 p-0 my-1">
                    <div class="d-flex align-items-start">
                        <button type="button" class="btn btn-info ml-2" data-toggle="modal" data-target="#modal-filter">
                            Filter
                        </button>
                        @if ($filter->has('tahun_pelajaran') || $filter->has('kelas'))
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
                @if ($filter->has('tahun_pelajaran') || $filter->has('kelas'))
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
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">FILTER DATA <span class="font-weight-bold">TRANSKRIP</span>
                        TERLEBIH DAHULU</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data">
                        <?php $data_nama_mata_pelajaran_x = []; ?>
                        @foreach ($data_guru_mata_pelajaran_x as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_x, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_x); ?>

                        <?php $data_nama_mata_pelajaran_xi = []; ?>
                        @foreach ($data_guru_mata_pelajaran_xi as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_xi, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_xi); ?>

                        <?php $data_nama_mata_pelajaran_xii = []; ?>
                        @foreach ($data_guru_mata_pelajaran_xii as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_xii, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_xii); ?>

                        <?php $data_nama_mata_pelajaran_ijazah = []; ?>
                        @foreach ($data_mata_pelajaran_ijazah as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_ijazah, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_ijazah); ?>

                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" style="min-width: 40px;">No</th>
                                <th scope="col" rowspan="2" style="min-width: 80px;">NIS</th>
                                <th scope="col" rowspan="2" style="min-width: 240px;">Nama</th>
                                @foreach ($data_semester as $semester)
                                    <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_x) }}"
                                        class="text-center border">
                                        Semester {{ $semester }}
                                    </th>
                                @endforeach
                                @foreach ($data_semester as $semester)
                                    <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_xi) }}"
                                        class="text-center border">
                                        Semester {{ $semester + 2 }}
                                    </th>
                                @endforeach
                                @foreach ($data_semester as $semester)
                                    <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_xii) }}"
                                        class="text-center border">
                                        Semester {{ $semester + 4 }}
                                    </th>
                                @endforeach
                                <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_ijazah) }}"
                                    class="text-center border">Nilai Ijazah</th>
                            </tr>
                            <tr>
                                @foreach ($data_semester as $semester)
                                    @foreach ($data_nama_mata_pelajaran_x as $nama_mata_pelajaran)
                                        <th scope="col" class="text-center border">
                                            {{ $nama_mata_pelajaran[1] }}</th>
                                    @endforeach
                                @endforeach

                                @foreach ($data_semester as $semester)
                                    @foreach ($data_nama_mata_pelajaran_xi as $nama_mata_pelajaran)
                                        <th scope="col" class="text-center border">
                                            {{ $nama_mata_pelajaran[1] }}</th>
                                    @endforeach
                                @endforeach

                                @foreach ($data_semester as $semester)
                                    @foreach ($data_nama_mata_pelajaran_xii as $nama_mata_pelajaran)
                                        <th scope="col" class="text-center border">
                                            {{ $nama_mata_pelajaran[1] }}</th>
                                    @endforeach
                                @endforeach

                                @foreach ($data_nama_mata_pelajaran_ijazah as $nama_mata_pelajaran)
                                    <th scope="col" class="text-center border">
                                        {{ $nama_mata_pelajaran[1] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach ($data_siswa_aktif_xii as $siswa_aktif_xii)
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>{{ $siswa_aktif_xii->siswa->nis }}</td>
                                    <td>{{ $siswa_aktif_xii->siswa->nama }}</td>

                                    @foreach ($data_semester as $semester)
                                        <?php $data_total_nilai = []; ?>
                                        @foreach ($data_guru_mata_pelajaran_x as $guru_mata_pelajaran)
                                            <?php $nilai = '-'; ?>
                                            @foreach ($data_siswa_aktif_x->where('siswa_id', $siswa_aktif_xii->siswa_id)->first()->nilai->where('semester', $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                                <?php $nilai = $data_nilai->nilai; ?>
                                            @endforeach
                                            <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                        @endforeach
                                        <?php sort($data_total_nilai); ?>
                                        @foreach ($data_total_nilai as $total_nilai)
                                            <td>{{ $total_nilai[1] }}</td>
                                        @endforeach
                                    @endforeach

                                    @foreach ($data_semester as $semester)
                                        <?php $data_total_nilai = []; ?>
                                        @foreach ($data_guru_mata_pelajaran_xi as $guru_mata_pelajaran)
                                            <?php $nilai = '-'; ?>
                                            @foreach ($data_siswa_aktif_xi->where('siswa_id', $siswa_aktif_xii->siswa_id)->first()->nilai->where('semester', $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                                <?php $nilai = $data_nilai->nilai; ?>
                                            @endforeach
                                            <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                        @endforeach
                                        <?php sort($data_total_nilai); ?>
                                        @foreach ($data_total_nilai as $total_nilai)
                                            <td>{{ $total_nilai[1] }}</td>
                                        @endforeach
                                    @endforeach

                                    @foreach ($data_semester as $semester)
                                        <?php $data_total_nilai = []; ?>
                                        @foreach ($data_guru_mata_pelajaran_xii as $guru_mata_pelajaran)
                                            <?php $nilai = '-'; ?>
                                            @foreach ($siswa_aktif_xii->nilai->where('semester', $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                                <?php $nilai = $data_nilai->nilai; ?>
                                            @endforeach
                                            <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                        @endforeach
                                        <?php sort($data_total_nilai); ?>
                                        @foreach ($data_total_nilai as $total_nilai)
                                            <td>{{ $total_nilai[1] }}</td>
                                        @endforeach
                                    @endforeach

                                    <?php $data_total_nilai = []; ?>
                                    @foreach ($data_mata_pelajaran_ijazah as $mata_pelajaran_ijazah)
                                        <?php $nilai = '-'; ?>
                                        @foreach ($siswa_aktif_xii->nilai_ijazah->where('mata_pelajaran_id', $mata_pelajaran_ijazah->mata_pelajaran->id) as $data_nilai)
                                            <?php $nilai = $data_nilai->nilai; ?>
                                        @endforeach
                                        <?php array_push($data_total_nilai, [$mata_pelajaran_ijazah->mata_pelajaran->urutan, $nilai]); ?>
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
                <form action="{{ route('admin.transkrip') }}" method="get" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Filter Data <span
                                class="text-primary">Transkrip</span>
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
                            <label for="kelas">Kelas</label>
                            <select class="form-control" autocomplete="off" id="kelas" name="kelas">
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
                <form action="{{ route('admin.transkrip.print') }}" method="get" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Transkrip Kelas <span
                                class="text-primary"> {{ $filter->kelas ? $filter->kelas : '' }}</span></h5>
                        </span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="tanggal_transkrip">Tanggal Transkrip <span class="text-danger">*</span></label>
                        <input type="date" required
                            class="form-control @error('tanggal_transkrip') is-invalid @enderror" id="tanggal_transkrip"
                            name="tanggal_transkrip" value="">
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
            const elKelas = document.getElementById('kelas');

            const changeKelas = () => {
                const angkatan = 'XII';
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
                        <button type="button" class="btn btn-primary ml-2" data-toggle="modal"
                            data-target="#modalPrint">
                            Print PDF
                        </button>
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
                            <td class="h6 text-primary">
                                <b>{{ $kelas }}</b>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered data">
                        <?php $data_nama_mata_pelajaran_x = []; ?>
                        @foreach ($data_guru_mata_pelajaran_x as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_x, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_x); ?>

                        <?php $data_nama_mata_pelajaran_xi = []; ?>
                        @foreach ($data_guru_mata_pelajaran_xi as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_xi, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_xi); ?>

                        <?php $data_nama_mata_pelajaran_xii = []; ?>
                        @foreach ($data_guru_mata_pelajaran_xii as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_xii, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_xii); ?>

                        <?php $data_nama_mata_pelajaran_ijazah = []; ?>
                        @foreach ($data_mata_pelajaran_ijazah as $guru_mata_pelajaran)
                            <?php array_push($data_nama_mata_pelajaran_ijazah, [$guru_mata_pelajaran->mata_pelajaran->urutan, $guru_mata_pelajaran->mata_pelajaran->kode]); ?>
                        @endforeach
                        <?php sort($data_nama_mata_pelajaran_ijazah); ?>

                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" style="min-width: 40px;">No</th>
                                <th scope="col" rowspan="2" style="min-width: 80px;">NIS</th>
                                <th scope="col" rowspan="2" style="min-width: 240px;">Nama</th>
                                @foreach ($data_semester as $semester)
                                    <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_x) }}"
                                        class="text-center border">
                                        Semester {{ $semester }}
                                    </th>
                                @endforeach
                                @foreach ($data_semester as $semester)
                                    <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_xi) }}"
                                        class="text-center border">
                                        Semester {{ $semester + 2 }}
                                    </th>
                                @endforeach
                                @foreach ($data_semester as $semester)
                                    <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_xii) }}"
                                        class="text-center border">
                                        Semester {{ $semester + 4 }}
                                    </th>
                                @endforeach
                                <th scope="col" colspan="{{ count($data_nama_mata_pelajaran_ijazah) }}"
                                    class="text-center border">Nilai Ijazah</th>
                            </tr>
                            <tr>
                                @foreach ($data_semester as $semester)
                                    @foreach ($data_nama_mata_pelajaran_x as $nama_mata_pelajaran)
                                        <th scope="col" class="text-center border">
                                            {{ $nama_mata_pelajaran[1] }}</th>
                                    @endforeach
                                @endforeach

                                @foreach ($data_semester as $semester)
                                    @foreach ($data_nama_mata_pelajaran_xi as $nama_mata_pelajaran)
                                        <th scope="col" class="text-center border">
                                            {{ $nama_mata_pelajaran[1] }}</th>
                                    @endforeach
                                @endforeach

                                @foreach ($data_semester as $semester)
                                    @foreach ($data_nama_mata_pelajaran_xii as $nama_mata_pelajaran)
                                        <th scope="col" class="text-center border">
                                            {{ $nama_mata_pelajaran[1] }}</th>
                                    @endforeach
                                @endforeach

                                @foreach ($data_nama_mata_pelajaran_ijazah as $nama_mata_pelajaran)
                                    <th scope="col" class="text-center border">
                                        {{ $nama_mata_pelajaran[1] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach ($data_siswa_aktif_xii as $siswa_aktif_xii)
                                <tr>
                                    <td>
                                        <?= $count ?>
                                    </td>
                                    <td>{{ $siswa_aktif_xii->siswa->nis }}</td>
                                    <td>{{ $siswa_aktif_xii->siswa->nama }}</td>

                                    @foreach ($data_semester as $semester)
                                        <?php $data_total_nilai = []; ?>
                                        @foreach ($data_guru_mata_pelajaran_x as $guru_mata_pelajaran)
                                            <?php $nilai = '-'; ?>
                                            @foreach ($data_siswa_aktif_x->where('siswa_id', $siswa_aktif_xii->siswa_id)->first()->nilai->where('semester', $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                                <?php $nilai = $data_nilai->nilai; ?>
                                            @endforeach
                                            <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                        @endforeach
                                        <?php sort($data_total_nilai); ?>
                                        @foreach ($data_total_nilai as $total_nilai)
                                            <td>{{ $total_nilai[1] }}</td>
                                        @endforeach
                                    @endforeach

                                    @foreach ($data_semester as $semester)
                                        <?php $data_total_nilai = []; ?>
                                        @foreach ($data_guru_mata_pelajaran_xi as $guru_mata_pelajaran)
                                            <?php $nilai = '-'; ?>
                                            @foreach ($data_siswa_aktif_xi->where('siswa_id', $siswa_aktif_xii->siswa_id)->first()->nilai->where('semester', $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                                <?php $nilai = $data_nilai->nilai; ?>
                                            @endforeach
                                            <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                        @endforeach
                                        <?php sort($data_total_nilai); ?>
                                        @foreach ($data_total_nilai as $total_nilai)
                                            <td>{{ $total_nilai[1] }}</td>
                                        @endforeach
                                    @endforeach

                                    @foreach ($data_semester as $semester)
                                        <?php $data_total_nilai = []; ?>
                                        @foreach ($data_guru_mata_pelajaran_xii as $guru_mata_pelajaran)
                                            <?php $nilai = '-'; ?>
                                            @foreach ($siswa_aktif_xii->nilai->where('semester', $semester)->where('mata_pelajaran_id', $guru_mata_pelajaran->mata_pelajaran->id) as $data_nilai)
                                                <?php $nilai = $data_nilai->nilai; ?>
                                            @endforeach
                                            <?php array_push($data_total_nilai, [$guru_mata_pelajaran->mata_pelajaran->urutan, $nilai]); ?>
                                        @endforeach
                                        <?php sort($data_total_nilai); ?>
                                        @foreach ($data_total_nilai as $total_nilai)
                                            <td>{{ $total_nilai[1] }}</td>
                                        @endforeach
                                    @endforeach

                                    <?php $data_total_nilai = []; ?>
                                    @foreach ($data_mata_pelajaran_ijazah as $mata_pelajaran_ijazah)
                                        <?php $nilai = '-'; ?>
                                        @foreach ($siswa_aktif_xii->nilai_ijazah->where('mata_pelajaran_id', $mata_pelajaran_ijazah->mata_pelajaran->id) as $data_nilai)
                                            <?php $nilai = $data_nilai->nilai; ?>
                                        @endforeach
                                        <?php array_push($data_total_nilai, [$mata_pelajaran_ijazah->mata_pelajaran->urutan, $nilai]); ?>
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
                <form action="{{ route('admin.transkrip.print') }}" method="get" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Transkrip Kelas <span
                                class="text-primary"> {{ $kelas }}</span></h5>
                        </span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="tanggal_transkrip">Tanggal Transkrip <span class="text-danger">*</span></label>
                        <input type="date" required
                            class="form-control @error('tanggal_transkrip') is-invalid @enderror" id="tanggal_transkrip"
                            name="tanggal_transkrip" value="">
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
