@extends('layouts.admin')
@section('nav_item-raport_p5', 'active')

@section('title', 'Raport P5')

@section('content')
<div class="card mb-4">
  <div class="card-header row">
    <div class="col-12 col-sm-6 p-0 my-1">
      <div class="d-flex align-items-start">
        <a href={{ route('admin.raport_p5.projek') }} type="button" class="btn btn-warning ml-2">
          Edit
        </a>
        <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalPrint">
          Print PDF
        </button>
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
    <div class="table-responsive">
      <table class="table table-striped table-bordered data">
        <thead>
          <tr>
            <th scope="col" style="width: 40px;">No</th>
            <th scope="col">NIS</th>
            <th scope="col">Nama</th>
          </tr>
        </thead>
        <tbody>
          <td>1</td>
          <td>12345</td>
          <td>Lorem Ipsum</td>
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
      <form action="{{ route('admin.raport_p5.print') }}" method="get" class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Yakin Cetak Raport P5 Kelas <span class="text-primary"> {{
                      $filter->kelas ? $filter->kelas : '' }}</span> - Semester <span class="text-primary"> {{
                      $filter->semester ? $filter->semester
                      : ''
                      }}</span></h5>
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