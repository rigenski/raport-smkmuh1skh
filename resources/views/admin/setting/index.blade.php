@extends('layouts.admin')
@section('nav__item-setting', 'active')

@section('title', 'Setting')

@section('content')
<div class="card mb-4">
    <div class="card-header row">
        <div class="col-12 col-sm-6 p-0 my-1">
            <div class="d-flex align-items-start">
                <h4>Info Sekolah</h4>
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
        <form action="{{ route('admin.setting.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            @if($is_setting)
            <div class="form-group row">
                <label for="nama_sekolah" class="col-sm-3 col-form-label">Nama Sekolah</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="nama_sekolah" name="nama_sekolah"
                        value="{{ $setting->sekolah }}">
                </div>
            </div>
            <div class=" form-group row">
                <label for="nama_kepala_sekolah" class="col-sm-3 col-form-label">Nama Kepala Sekolah</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="nama_kepala_sekolah" name="nama_kepala_sekolah"
                        value="{{ $setting->kepala_sekolah }}">
                </div>
            </div>
            <div class=" form-group row">
                <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="alamat" name="alamat" value="{{ $setting->alamat }}
                    ">
                </div>
            </div>
            <div class="form-group row">
                <label for="npsn" class="col-sm-3 col-form-label">NPSN</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="npsn" name="npsn" value="{{ $setting->npsn }}">
                </div>
            </div>
            <div class="form-group row">
                <label for="logo" class="col-sm-3 col-form-label">Logo</label>
                <div class="col-sm-9">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" accept="image/png, image/jpeg" id="logo"
                            name="logo">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <div class="d-flex flex-column mt-4">
                        <label for="logo_sekarang" class="mb-4 ml-2 text-primary"><b>[ Logo Sekarang
                                ]</b></label>
                        <img src="{{ asset('./images/setting/' . $setting->logo ) }}" alt="" style="max-width: 140px">
                    </div>
                </div>
            </div>
            @else
            <div class="form-group row">
                <label for="nama_sekolah" class="col-sm-3 col-form-label">Nama Sekolah</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="nama_sekolah" name="nama_sekolah">
                </div>
            </div>
            <div class="form-group row">
                <label for="nama_kepala_sekolah" class="col-sm-3 col-form-label">Nama Kepala Sekolah</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="nama_kepala_sekolah"
                        name="nama_kepala_sekolah">
                </div>
            </div>
            <div class="form-group row">
                <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="alamat" name="alamat">
                </div>
            </div>
            <div class="form-group row">
                <label for="npsn" class="col-sm-3 col-form-label">NPSN</label>
                <div class="col-sm-9">
                    <input type="text" required class="form-control" id="npsn" name="npsn">
                </div>
            </div>
            <div class="form-group row">
                <label for="logo" class="col-sm-3 col-form-label">Logo</label>
                <div class="col-sm-9">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" accept="image/png, image/jpeg" id="logo"
                            name="logo">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
            </div>
            @endif
    </div>
    <div class="card-footer bg-whitesmoke text-md-right">
        <button type="submit" class="btn btn-primary" id="save-btn">Simpan</button>
        </form>
    </div>
</div>

@endsection