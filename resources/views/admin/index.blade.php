@extends('layouts.admin')
@section('nav__item-admin', 'active')

@section('title', 'Dashboard')

@section('content')
<div class="row">
  <div class="col-lg- col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary">
        <i class="far fa-user"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Lorem Ipsum</h4>
        </div>
        <div class="card-body">
          123
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg- col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-info">
        <i class="far fa-user"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Lorem Ipsum</h4>
        </div>
        <div class="card-body">
          123
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Lorem Ipsum</h4>
      </div>
      <div class="card-body">

      </div>
    </div>
  </div>
</div>
@endsection