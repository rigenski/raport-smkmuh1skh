<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Dashboard Raport SMK Muhammadiyah 1 Sukoharjo</title>
  <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('/css/all.css')}}">
  <link rel="stylesheet" href="{{asset('/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('/css/components.css')}}">
  <link rel="stylesheet" href="{{asset('/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/css/bootstrap-datepicker.min.css')}}">
  <link rel="stylesheet" href="{{asset('/css/admin.css')}}">
</head>

<body>

  <div id="app" class="">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <i class="fas fa-user mr-2"></i>
              <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->role == 'admin' ? 'Admin' :
                auth()->user()->guru->nama }}</div>
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
            <a href="/admin">RAPORT Mutuharjo</a>
          </div>
          <ul class="sidebar-menu">
            @if(auth()->user()->role === 'admin')
            <li class="menu-header">MAIN</li>
            <li class="@yield('nav__item-admin')">
              <a class="nav-link" href="{{ route('admin') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="@yield('nav__item-setting')">
              <a class="nav-link" href="{{ route('admin.setting') }}">
                <i class="fas fa-cog"></i>
                <span>Setting</span>
              </a>
            </li>
            <li class="menu-header">UNIT</li>
            <li class="@yield('nav__item-guru')">
              <a class="nav-link" href="{{ route('admin.guru') }}">
                <i class="fas fa-users"></i>
                <span>Guru</span>
              </a>
            </li>
            <li class="@yield('nav__item-wali__kelas')">
              <a class="nav-link" href="{{ route('admin.wali_kelas') }}">
                <i class="fas fa-users"></i>
                <span>Wali Kelas</span>
              </a>
            </li>
            <li class="@yield('nav__item-siswa')">
              <a class="nav-link" href="{{ route('admin.siswa') }}">
                <i class="fas fa-users"></i>
                <span>Siswa</span>
              </a>
            </li>
            <li class="@yield('nav__item-mapel')">
              <a class="nav-link" href="{{ route('admin.mapel') }}">
                <i class="fas fa-book"></i>
                <span>Mapel</span>
              </a>
            </li>
            <li class="menu-header">OTHER</li>
            <li class="@yield('nav__item-nilai')">
              <a class="nav-link" href="{{ route('admin.nilai') }}">
                <i class="fas fa-sort-numeric-up"></i>
                <span>Nilai</span>
              </a>
            </li>
            <li class="@yield('nav__item-ranking')">
              <a class="nav-link" href="{{ route('admin.ranking') }}">
                <i class="fas fa-trophy"></i>
                <span>Ranking</span>
              </a>
            </li>
            <li class="@yield('nav__item-raport')">
              <a class="nav-link" href="{{ route('admin.raport') }}">
                <i class="fas fa-book"></i>
                <span>Raport</span>
              </a>
            </li>
            <li class="@yield('nav__item-riwayat')">
              <a class="nav-link" href="{{ route('admin.riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
              </a>
            </li>

            @elseif(auth()->user()->role === 'guru')
            <li class="menu-header">MAIN</li>
            <li class="@yield('nav__item-admin')">
              <a class="nav-link" href="{{ route('admin') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="@yield('nav__item-nilai')">
              <a class="nav-link" href="{{ route('admin.nilai') }}">
                <i class="fas fa-sort-numeric-up"></i>
                <span>Nilai</span>
              </a>
            </li>
            @elseif(auth()->user()->role === 'wali kelas')
            <li class="menu-header">MAIN</li>
            <li class="@yield('nav__item-admin')">
              <a class="nav-link" href="{{ route('admin') }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
              </a>
            </li>
            <li class="@yield('nav__item-nilai')">
              <a class="nav-link" href="{{ route('admin.nilai') }}">
                <i class="fas fa-sort-numeric-up"></i>
                <span>Nilai</span>
              </a>
            </li>
            <li class="@yield('nav__item-raport')">
              <a class="nav-link" href="{{ route('admin.raport') }}">
                <i class="fas fa-book"></i>
                <span>Raport</span>
              </a>
            </li>
            @endif
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>@yield('title')</h1>
          </div>
          <div class="section-body">
            @yield('content')
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          PPDB - SMK Muhammadiyah 1 Sukoharjo
        </div>
      </footer>
    </div>
  </div>

  @yield('modal')

  <script src="{{ asset('/js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('/js/popper.min.js') }}">
  </script>
  <script src="{{ asset('/js/bootstrap.min.js') }}">
  </script>
  <script src="{{ asset('/js/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('/js/moment.min.js') }}"></script>
  <script src="{{ asset('/js/stisla.js') }}"></script>
  <script src="{{ asset('/js/scripts.js') }}"></script>
  <script src="{{ asset('/js/custom.js') }}"></script>
  <script src="{{ asset('/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{ asset('/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{ asset('/js/bootstrap-datepicker.min.js')}}"></script>
  <script src="{{ asset('/js/bootstrap-multiselect.js')}}"></script>
  <script src="{{ asset('/js/chart.js')}}"></script>
  <script>
    $(document).ready(function(){
        $('.data').DataTable();

        $(".datepicker").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom auto"
        });

        $('.multi-select').multiselect({
            enableClickableOptGroups: true,
            enableCollapsibleOptGroups: true,
            enableFiltering: true,
            includeSelectAllOption: true
        });
    });
  </script>

  @yield('script')
</body>

</html>