@extends('layouts.app')

@section('main')
    <div class="main-wrapper">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
            </form>
            <ul class="navbar-nav navbar-right">
                <li class="dropdown"><a href="#" data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <i class="fas fa-user mr-2"></i>
                        <div class="d-sm-none d-lg-inline-block">Hi,
                            {{ auth()->user()->role == 'admin' ? 'Admin' : auth()->user()->guru->nama }}</div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="main-sidebar">
            <aside id="sidebar-wrapper">
                <div class="sidebar-brand">
                    <h4
                        style="color: #6777ef;margin: 0;margin-top: 18px;margin-bottom: -18px;text-align: left;margin-left: 14px;">
                        SiMa<span style="color: orange;">-Ku</span></h4>
                    <a href="/admin" style="letter-spacing: 1px;text-transform: none;">Sistem Manajemen Kurikulum</a>
                </div>
                <ul class="sidebar-menu" style="padding-bottom: 200px;padding-top: 8px;">
                    @if (auth()->user()->role === 'admin')
                        <li class="menu-header">UTAMA</li>
                        <li class="@yield('nav_item-admin')">
                            <a class="nav-link" href="{{ route('admin') }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-setting')">
                            <a class="nav-link" href="{{ route('admin.setting') }}">
                                <i class="fas fa-cog"></i>
                                <span>Setting</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-dokumen')">
                            <a class="nav-link" href="{{ route('admin.dokumen') }}">
                                <i class="fas fa-file"></i>
                                <span>Dokumen</span>
                            </a>
                        </li>
                        <li class="menu-header">MATA PELAJARAN</li>
                        <li class="@yield('nav_item-mata_pelajaran')">
                            <a class="nav-link" href="{{ route('admin.mata_pelajaran') }}">
                                <i class="fas fa-book"></i>
                                <span>Mata Pelajaran</span>
                            </a>
                        </li>
                        <li class="menu-header">GURU</li>
                        <li class="@yield('nav_item-guru')">
                            <a class="nav-link" href="{{ route('admin.guru') }}">
                                <i class="fas fa-users"></i>
                                <span>Guru</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-wali_kelas')">
                            <a class="nav-link" href="{{ route('admin.wali_kelas') }}">
                                <i class="fas fa-users"></i>
                                <span>Wali Kelas</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-guru_mata_pelajaran')">
                            <a class="nav-link" href="{{ route('admin.guru_mata_pelajaran') }}">
                                <i class="fas fa-users"></i>
                                <span>Guru Mata Pelajaran</span>
                            </a>
                        </li>
                        {{-- <li class="@yield('nav_item-guru_raport_p5')">
                            <a class="nav-link" href="{{ route('admin.guru_raport_p5') }}">
                                <i class="fas fa-users"></i>
                                <span>Guru Raport P5</span>
                            </a>
                        </li> --}}
                        <li class="menu-header">SISWA</li>
                        <li class="@yield('nav_item-siswa')">
                            <a class="nav-link" href="{{ route('admin.siswa') }}">
                                <i class="fas fa-users"></i>
                                <span>Siswa</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-siswa_aktif')">
                            <a class="nav-link" href="{{ route('admin.siswa_aktif') }}">
                                <i class="fas fa-users"></i>
                                <span>Siswa Aktif</span>
                            </a>
                        </li>
                        <li class="menu-header">TAMBAHAN</li>
                        <li class="@yield('nav_item-ekskul')">
                            <a class="nav-link" href="{{ route('admin.ekskul') }}">
                                <i class="fas fa-thumbtack"></i>
                                <span>Ekskul</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-ketidakhadiran')">
                            <a class="nav-link" href="{{ route('admin.ketidakhadiran') }}">
                                <i class="fas fa-paperclip"></i>
                                <span>Ketidakhadiran</span>
                            </a>
                        </li>
                        <li class="menu-header">NILAI</li>
                        <li class="@yield('nav_item-nilai')">
                            <a class="nav-link" href="{{ route('admin.nilai') }}">
                                <i class="fas fa-sort-numeric-up"></i>
                                <span>Nilai</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-raport')">
                            <a class="nav-link" href="{{ route('admin.raport') }}">
                                <i class="fas fa-book"></i>
                                <span>Raport</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-ranking')">
                            <a class="nav-link" href="{{ route('admin.ranking') }}">
                                <i class="fas fa-trophy"></i>
                                <span>Ranking</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-riwayat')">
                            <a class="nav-link" href="{{ route('admin.riwayat') }}">
                                <i class="fas fa-history"></i>
                                <span>Riwayat</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-transkrip')">
                            <a class="nav-link" href="{{ route('admin.transkrip') }}">
                                <i class="fas fa-book"></i>
                                <span>Transkrip</span>
                            </a>
                        </li>
                        {{-- <li class="menu-header">Nilai Lanjutan</li>
                        <li class="@yield('nav_item-raport_p5')">
                            <a class="nav-link" href="{{ route('admin.raport_p5') }}">
                                <i class="fas fa-book"></i>
                                <span>Raport P5</span>
                            </a>
                        </li> --}}
                    @elseif(auth()->user()->role === 'guru')
                        <li class="menu-header">UTAMA</li>
                        <li class="@yield('nav_item-admin')">
                            <a class="nav-link" href="{{ route('admin') }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-nilai')">
                            <a class="nav-link" href="{{ route('admin.nilai') }}">
                                <i class="fas fa-sort-numeric-up"></i>
                                <span>Nilai</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-dokumen')">
                            <a class="nav-link" href="{{ route('admin.dokumen') }}">
                                <i class="fas fa-file"></i>
                                <span>Dokumen</span>
                            </a>
                        </li>
                        {{-- <li class="menu-header">Nilai Lanjutan</li>
                        <li class="@yield('nav_item-raport_p5')">
                            <a class="nav-link" href="{{ route('admin.raport_p5') }}">
                                <i class="fas fa-book"></i>
                                <span>Nilai P5</span>
                            </a>
                        </li> --}}
                    @elseif(auth()->user()->role === 'wali kelas')
                        <li class="menu-header">UTAMA</li>
                        <li class="@yield('nav_item-admin')">
                            <a class="nav-link" href="{{ route('admin') }}">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-ekskul')">
                            <a class="nav-link" href="{{ route('admin.ekskul') }}">
                                <i class="fas fa-thumbtack"></i>
                                <span>Ekskul</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-ketidakhadiran')">
                            <a class="nav-link" href="{{ route('admin.ketidakhadiran') }}">
                                <i class="fas fa-paperclip"></i>
                                <span>Ketidakhadiran</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-nilai')">
                            <a class="nav-link" href="{{ route('admin.nilai') }}">
                                <i class="fas fa-sort-numeric-up"></i>
                                <span>Nilai</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-raport')">
                            <a class="nav-link" href="{{ route('admin.raport') }}">
                                <i class="fas fa-book"></i>
                                <span>Raport</span>
                            </a>
                        </li>
                        <li class="@yield('nav_item-dokumen')">
                            <a class="nav-link" href="{{ route('admin.dokumen') }}">
                                <i class="fas fa-file"></i>
                                <span>Dokumen</span>
                            </a>
                        </li>
                        {{-- <li class="menu-header">Nilai Lanjutan</li>
                        <li class="@yield('nav_item-raport_p5')">
                            <a class="nav-link" href="{{ route('admin.raport_p5') }}">
                                <i class="fas fa-book"></i>
                                <span>Nilai P5</span>
                            </a>
                        </li> --}}
                    @endif
                </ul>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <section class="section">
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h1 class="m-0">@yield('title')</h1>
                        <h5 class="m-0 text-primary">{{ count($setting) ? $setting[0]->tahun_pelajaran : '-' }}</h5>
                    </div>
                </div>
                <div class="section-body">
                    @yield('content')
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <div class="footer-left">
                DASHBOARD RAPORT
            </div>
        </footer>
    </div>
@endsection
