@extends('layouts.admin')
@section('nav_item-raport_p5', 'active')

@section('title', 'Raport P5')

@section('content')
<form action={{ route('admin.raport_p5.elemen.edit') }} method="post" class="card mb-4">
  @csrf
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 my-1">
      <h4 class="mb-0">ELEMEN</h4>
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
        <div class="form-group mb-2">
          <label for="projek_id">Pilih Projek<span class="text-danger">*</span></label>
          <select class="form-control" autocomplete="off" id="projek_id" name="projek_id" onchange="javascript:handleSelectProjek(this)">
            @if(!$request->projek_id)
              <option value="">-- Pilih Projek --</option>
            @endif
            @foreach($data_raport_p5_projek as $raport_p5_projek)
              @if($request->projek_id == $raport_p5_projek->id)
              <option value={{ $raport_p5_projek->id }} selected>{{ $raport_p5_projek->nama }}</option>
              @else
              <option value={{ $raport_p5_projek->id }}>{{ $raport_p5_projek->nama }}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
      @if($request->projek_id)
      <div class="col-12">
        <div class="form-group mb-3">
          <label for="dimensi_id">Pilih Dimensi<span class="text-danger">*</span></label>
          <select class="form-control" autocomplete="off" id="dimensi_id" name="dimensi_id" onchange="javascript:handleSelectDimensi(this)"> 
            @if(!$request->dimensi_id)
              <option value="">-- Pilih Dimensi --</option>
            @endif
            @foreach($data_raport_p5_dimensi as $raport_p5_dimensi)
              @if($request->dimensi_id == $raport_p5_dimensi->id)
              <option value={{ $raport_p5_dimensi->id }} selected>{{ $raport_p5_dimensi->nama }}</option>
              @else
              <option value={{ $raport_p5_dimensi->id }}>{{ $raport_p5_dimensi->nama }}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
      @endif
      @if(count($data_raport_p5_elemen) && $request->projek_id && $request->dimensi_id)
      @foreach($data_raport_p5_elemen as $raport_p5_elemen)
        <div id="form-clone" class="mb-2 col-12 col-md-6">
          <input type="hidden" name="id[]" value={{ $raport_p5_elemen->id}}>
          <div class="d-flex justify-content-between">
            <h6 class="mb-0 text-primary"># 1</h6>
            <button type="button" id="button-remove" class="text-danger bg-transparent border-0 font-weight-bold"><u>Hapus</u></button>
          </div>
          <div class="form-group mb-2">
            <label for="sub_elemen">Sub Elemen<span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('sub_elemen') is-invalid @enderror" id="sub_elemen" name="sub_elemen[]" value={{ $raport_p5_elemen->sub_elemen }}
            autocomplete="off" required>
          </div>
          <div class="form-group mb-2">
            <label for="akhir_fase">Akhir Fase<span class="text-danger">*</span></label>
            <textarea class="form-control @error('akhir_fase') is-invalid @enderror" rows="3" name="akhir_fase[]" required>{{ $raport_p5_elemen->akhir_fase }}</textarea>
          </div>
        </div>
      @endforeach
      @elseif($request->projek_id && $request->dimensi_id)
        <div id="form-clone" class="mb-2 col-12 col-md-6">
          <input type="hidden" name="id[]" value="0">
          <div class="d-flex justify-content-between">
            <h6 class="mb-0 text-primary"># 1</h6>
            <button type="button" id="button-remove" class="text-danger bg-transparent border-0 font-weight-bold"><u>Hapus</u></button>
          </div>
          <div class="form-group mb-2">
            <label for="sub_elemen">Sub Elemen<span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('sub_elemen') is-invalid @enderror" id="sub_elemen" name="sub_elemen[]"
            autocomplete="off" required>
          </div>
          <div class="form-group mb-2">
            <label for="akhir_fase">Akhir Fase<span class="text-danger">*</span></label>
            <textarea class="form-control @error('akhir_fase') is-invalid @enderror" rows="3" name="akhir_fase[]" required></textarea>
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
          <a href={{ route('admin.raport_p5.dimensi') }} type="button" class="btn mr-2 btn-warning">Sebelumnya</a>
          <a href={{ route('admin.raport_p5.catatan') }} type="button" class="btn btn-primary">Selanjutnya</a>
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
              <label for="sub_elemen">Sub Elemen<span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('sub_elemen') is-invalid @enderror" id="sub_elemen" name="sub_elemen[]"
              autocomplete="off" required>
            </div>
            <div class="form-group mb-2">
              <label for="akhir_fase">Akhir Fase<span class="text-danger">*</span></label>
              <textarea class="form-control @error('akhir_fase') is-invalid @enderror" rows="3" name="akhir_fase[]" required></textarea>
            </div>
          </div>`
    ;
 
      $('#form-container').append(newForm);
    });
  
    $("body").on("click", "#button-remove", function () {
        $(this).parents("#form-clone").remove();
    })

    function handleSelectProjek (e) {
      window.location.href = `?projek_id=${e.value}`;
    }

    function handleSelectDimensi (e) {
      window.location.href = `?projek_id=<?= $request->projek_id ?>&dimensi_id=${e.value}`;
    }
  </script>
@endsection