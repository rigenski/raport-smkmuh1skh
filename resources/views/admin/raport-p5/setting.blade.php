@extends('layouts.admin')
@section('nav_item-raport_p5', 'active')

@section('title', 'Raport P5')

@section('content')
<form action={{ route('admin.raport_p5.setting.edit') }} method="post" class="card mb-4">
  @csrf
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 my-1">
      <h4 class="mb-0">SETTING</h4>
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
    <div id="form-container" class="row">
      <div class="col-12">
        <div class="form-group mb-3">
          <label for="tahun_pelajaran">Tahun Pelajaran<span class="text-danger">*</span></label>
          <select class="form-control" autocomplete="off" id="tahun_pelajaran" name="tahun_pelajaran" required onchange="javascript:handleSelectTahunPelajaran(this)">
            @if(!$request->tahun_pelajaran)
              <option value="">-- Pilih Tahun Pelajaran --</option>
            @endif
            @foreach($data_tahun_pelajaran as $tahun_pelajaran)
              @if($request->tahun_pelajaran == $tahun_pelajaran)
              <option value={{ $tahun_pelajaran }} selected>{{ $tahun_pelajaran }}</option>
              @else
              <option value={{ $tahun_pelajaran }}>{{ $tahun_pelajaran }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="form-group mb-3">
          <label for="semester">Semester<span class="text-danger">*</span></label>
          <select class="form-control" autocomplete="off" id="semester" name="semester" required onchange="javascript:handleSelectSemester(this)">
            @if(!$request->semester)
              <option value="">-- Pilih Semester --</option>
            @endif
            @foreach($data_semester as $semester)
              @if($request->semester == $semester)
              <option value={{ $semester }} selected>{{ $semester }}</option>
              @else
              <option value={{ $semester }}>{{ $semester }}</option>
              @endif
            @endforeach
          </select>
        </div>
        @if($raport_p5)
        <div class="form-group mb-2">
          <label for="judul">Judul<span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ $raport_p5->judul }}"
          autocomplete="off" required>
        </div>
        <div class="form-group mb-2">
          <label for="catatan">Catatan<span class="text-danger">*</span></label>
          <textarea class="form-control @error('catatan') is-invalid @enderror" rows="3" name="catatan" 
            autocomplete="off" required>{{ $raport_p5->catatan}}</textarea>
        </div>
        @else
        <div class="form-group mb-2">
          <label for="judul">Judul<span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul"
          autocomplete="off" required>
        </div>
        <div class="form-group mb-2">
          <label for="catatan">Catatan<span class="text-danger">*</span></label>
          <textarea class="form-control @error('catatan') is-invalid @enderror" rows="3" name="catatan" 
            autocomplete="off" required></textarea>
        </div>
        @endif
      </div>
    </div>
  </div>
  <div class="card-footer">
    <div class="row px-3">
      <div class="col-12 col-sm-6 p-0 my-1">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      <div class="col-12 col-sm-6 p-0 my-1">
        <div class="d-flex justify-content-end">
          <a href={{ route('admin.raport_p5') }} type="button" class="btn mr-2 btn-warning">Sebelumnya</a>
          <a href={{ route('admin.raport_p5.projek', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester]) }} type="button" class="btn btn-primary">Selanjutnya</a>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection

@section('script')
  <script>
    function handleSelectTahunPelajaran (e) {
      window.location.href = `?tahun_pelajaran=${e.value}&semester={{ $request->semester }}`;
    }
    function handleSelectSemester (e) {
      window.location.href = `?tahun_pelajaran={{ $request->tahun_pelajaran }}&semester=${e.value}`;
    }
  </script>
@endsection