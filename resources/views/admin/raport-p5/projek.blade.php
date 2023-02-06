@extends('layouts.admin')
@section('nav_item-raport_p5', 'active')

@section('title', 'Raport P5')

@section('content')
<form action={{ route('admin.raport_p5.projek.edit') }} method="post" class="card mb-4">
  @csrf
  <input type="hidden" name="tahun_pelajaran" value="{{ $request->tahun_pelajaran }}">
  <input type="hidden" name="semester" value="{{ $request->semester }}">
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 my-1">
      <h4 class="mb-0">PROJEK</h4>
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
      @if(count($data_raport_p5_projek))
        @foreach($data_raport_p5_projek as $raport_p5_projek)
          <div id="form-clone" class="mb-2 col-12 col-md-6">
            <input type="hidden" name="id[]" value="{{ $raport_p5_projek->id}}">
            <div class="d-flex justify-content-between">
              <h6 class="mb-0 text-primary"># 1</h6>
              <button type="button" id="button-remove" class="text-danger bg-transparent border-0 font-weight-bold"><u>Hapus</u></button>
            </div>
            <div class="form-group mb-2">
              <label for="nama">Nama Projek<span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama[]" value="{{ $raport_p5_projek->nama}}"
              autocomplete="off" required>
            </div>
            <div class="form-group mb-2">
              <label for="deskripsi">Deskripsi<span class="text-danger">*</span></label>
              <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="3" name="deskripsi[]" required>{{ $raport_p5_projek->deskripsi}}</textarea>
            </div>
          </div>
        @endforeach
      @else
      <div id="form-clone" class="mb-2 col-12 col-md-6">
        <input type="hidden" name="id[]" value="0">
        <div class="d-flex justify-content-between">
          <h6 class="mb-0 text-primary"># 1</h6>
          <button type="button" id="button-remove" class="text-danger bg-transparent border-0 font-weight-bold"><u>Hapus</u></button>
        </div>
        <div class="form-group mb-2">
          <label for="nama">Nama Projek<span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama[]"
          autocomplete="off" required>
        </div>
        <div class="form-group mb-2">
          <label for="deskripsi">Deskripsi<span class="text-danger">*</span></label>
          <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="3" name="deskripsi[]" required></textarea>
        </div>
      </div>
      @endif
    </div>
  </div>
  <div class="card-footer">
    <div class="row px-3">
      <div class="col-12 col-sm-6 p-0 my-1">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" id="button-add" class="btn ml-2 btn-success">Tambah</button>
      </div>
      <div class="col-12 col-sm-6 p-0 my-1">
        <div class="d-flex justify-content-end">
          <a href={{ route('admin.raport_p5.setting', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester]) }} type="button" class="btn mr-2 btn-warning">Kembali</a>
          <a href={{ route('admin.raport_p5.dimensi', ['tahun_pelajaran' => $request->tahun_pelajaran, 'semester' => $request->semester]) }} type="button" class="btn btn-primary">Selanjutnya</a>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection


@section('script')
  <script>
    $("#button-add").click(function () {
      newForm = `<div id="form-clone" class="mb-2 col-12 col-md-6">
            <input type="hidden" name="id[]" value="0">
            <div class="d-flex justify-content-between">
              <h6 class="mb-0 text-primary"># 1</h6>
              <button type="button" id="button-remove" class="text-danger bg-transparent border-0 font-weight-bold"><u>Hapus</u></button>
            </div>
            <div class="form-group mb-2">
              <label for="nama">Nama Projek<span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama[]"
              autocomplete="off" required>
            </div>
            <div class="form-group mb-2">
              <label for="deskripsi">Deskripsi<span class="text-danger">*</span></label>
              <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="3" name="deskripsi[]" required></textarea>
            </div>
          </div>`
    ;
 
      $('#form-container').append(newForm);
    });
  
    $("body").on("click", "#button-remove", function () {
        $(this).parents("#form-clone").remove();
    })
  </script>
@endsection