@extends('layouts.admin')
@section('nav__item-riwayat', 'active')

@section('title', 'Riwayat')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered data">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Kelas</th>
                        @foreach($semester as $data)
                        <th scope="col">{{ $data }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="list-container">

                    <?php $count = 1; ?>
                    @foreach($kelas as $data)
                    <tr>
                        <td>
                            <?= $count ?>
                        </td>
                        <td>{{ $data->kelas }}</td>
                        @foreach($nilai->where('siswa_id', $data->id)->unique('semester')->values() as $data)
                        {{-- <td>
                            if()
                        </td> --}}
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

@endsection