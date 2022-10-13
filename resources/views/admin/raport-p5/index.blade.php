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
