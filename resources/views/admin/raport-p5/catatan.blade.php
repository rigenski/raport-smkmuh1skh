@extends('layouts.admin')
@section('nav_item-raport_p5', 'active')

@section('title', 'Raport P5')

@section('content')
<form action={{ route('admin.raport_p5.catatan.edit') }} method="post" class="card mb-4">
  @csrf
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 my-1">
      <h4 class="mb-0">CATATAN</h4>
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
        @if($raport_p5->catatan)
        <div id="form-clone" class="mb-2 col-12">
          <div class="form-group mb-2">
            <label for="catatan">Catatan<span class="text-danger">*</span></label>
            <textarea class="form-control @error('catatan') is-invalid @enderror" rows="3" name="catatan" required>{{ $raport_p5->catatan }}</textarea>
          </div>
        </div>
        @else
        <div id="form-clone" class="mb-2 col-12">
          <div class="form-group mb-2">
            <label for="catatan">Catatan<span class="text-danger">*</span></label>
            <textarea class="form-control @error('catatan') is-invalid @enderror" rows="3" name="catatan" required></textarea>
          </div>
        </div>
        @endif
      </div>
  </div>
  <div class="card-footer">
    <div class="row px-3">
      <div class="col-12 col-sm-6 p-0 my-1">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      <div class="col-12 col-sm-6 p-0 my-1">
        <div class="d-flex justify-content-end">
          <a href={{ route('admin.raport_p5.elemen') }}  type="button" class="btn mr-2 btn-warning">Sebelumnya</a>
          <a href={{ route('admin.raport_p5') }} type="button" class="btn btn-primary">Selesai</a>
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
            <div class="d-flex justify-content-between">
              <h6 class="mb-0 text-primary"># 1</h6>
              <button type="button" id="button-remove" class="text-danger bg-transparent border-0 font-weight-bold"><u>Hapus</u></button>
            </div>
            <div class="form-group mb-2">
              <label for="nama">Nama Projek<span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              autocomplete="off" required>
            </div>
            <div class="form-group mb-2">
              <label for="deskripsi">Deskripsi<span class="text-danger">*</span></label>
              <textarea class="form-control @error('deskripsi') is-invalid @enderror" rows="3" name="deskripsi" required></textarea>
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