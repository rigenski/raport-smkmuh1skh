@extends('layouts.admin')
@section('nav_item-setting', 'active')

@section('title', 'Setting')

@section('content')
    <form class="card mb-4" action="{{ route('admin.setting.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-header row">
            <div class="col-12 col-sm-6 p-0 my-1">
                <div class="d-flex align-items-start">
                    <h4>Informasi</h4>
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
            @if (count($setting))
                <?php $setting = $setting[0]; ?>
                <div class="form-group row">
                    <label for="nama_sekolah" class="col-sm-3 col-form-label">Nama Sekolah <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah"
                            value="{{ $setting->sekolah }}" required>
                    </div>
                </div>
                <div class=" form-group row">
                    <label for="nama_kepala_sekolah" class="col-sm-3 col-form-label">Nama Kepala Sekolah <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama_kepala_sekolah" name="nama_kepala_sekolah"
                            value="{{ $setting->kepala_sekolah }}" required>
                    </div>
                </div>
                <div class=" form-group row">
                    <label for="alamat" class="col-sm-3 col-form-label">Alamat <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="alamat" name="alamat"
                            value="{{ $setting->alamat }}
                    " required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npsn" class="col-sm-3 col-form-label">NPSN <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="npsn" name="npsn"
                            value="{{ $setting->npsn }}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tahun_pelajaran" class="col-sm-3 col-form-label">Tahun Pelajaran <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control" id="tahun_pelajaran" name="tahun_pelajaran" required>
                            @if ($setting->tahun_pelajaran)
                                <option value="{{ $setting->tahun_pelajaran }}">{{ $setting->tahun_pelajaran }}</option>
                                @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                    @if ($setting->tahun_pelajaran !== $tahun_pelajaran)
                                        <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                    <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="logo" class="col-sm-3 col-form-label">Logo</label>
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" accept="image/png, image/jpeg" id="logo"
                                name="logo">
                            <label class="custom-file-label" for="logo">Pilih File</label>
                        </div>
                        <div class="d-flex flex-column mt-4">
                            <label for="logo_sekarang" class="mb-4 ml-2 text-primary"><b>( Logo Sekarang
                                    )</b></label>
                            <img src="{{ asset('./images/setting/' . $setting->logo) }}" alt=""
                                style="max-width: 80px">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="letterhead" class="col-sm-3 col-form-label">Kop</label>
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" accept="image/png, image/jpeg" id="letterhead"
                                name="letterhead">
                            <label class="custom-file-label" for="letterhead">Pilih File</label>
                        </div>
                        <div class="d-flex flex-column mt-4">
                            <label for="letterhead_sekarang" class="mb-4 ml-2 text-primary"><b>( Kop Sekarang
                                    )</b></label>
                            <img src="{{ asset('./images/setting/' . $setting->letterhead) }}" alt=""
                                style="max-width: 320px">
                        </div>
                    </div>
                </div>
            @else
                <div class="form-group row">
                    <label for="nama_sekolah" class="col-sm-3 col-form-label">Nama Sekolah <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama_kepala_sekolah" class="col-sm-3 col-form-label">Nama Kepala Sekolah <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama_kepala_sekolah" name="nama_kepala_sekolah"
                            required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="alamat" class="col-sm-3 col-form-label">Alamat <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="npsn" class="col-sm-3 col-form-label">NPSN <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="npsn" name="npsn" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tahun_pelajaran" class="col-sm-3 col-form-label">Tahun Pelajaran <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <select class="form-control" id="tahun_pelajaran" name="tahun_pelajaran" required>
                            @foreach ($data_tahun_pelajaran as $tahun_pelajaran)
                                <option value="{{ $tahun_pelajaran }}">{{ $tahun_pelajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="logo" class="col-sm-3 col-form-label">Logo <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" accept="image/png, image/jpeg"
                                id="logo" name="logo">
                            <label class="custom-file-label" for="logo">Pilih File</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="letterhead" class="col-sm-3 col-form-label">Kop <span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" accept="image/png, image/jpeg"
                                id="letterhead" name="letterhead">
                            <label class="custom-file-label" for="letterhead">Pilih File</label>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer bg-whitesmoke text-md-right">
            <button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
        </div>
    </form>

@endsection
