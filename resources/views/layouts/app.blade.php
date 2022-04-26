<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Dashboard Raport</title>

  <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('/css/all.css')}}">
  <link rel="stylesheet" href="{{asset('/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('/css/components.css')}}">
  <link rel="stylesheet" href="{{asset('/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('/css/bootstrap-datepicker.min.css')}}">
</head>

<body>
  <div id="app">
    @yield('main')
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
  <script src="{{ asset('/js/jquery.dataTables.min.js')}}" crossorigin="anonymous"></script>
  <script src="{{ asset('/js/dataTables.bootstrap4.min.js')}}" crossorigin="anonymous"></script>
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