<!DOCTYPE html>
<html lang="en" data-sidenav-size="{{ $sidenav ?? 'default' }}" data-layout-mode="{{ $layoutMode ?? 'fluid' }}" data-layout-position="{{ $position ?? 'fixed' }}" data-menu-color="{{ $menuColor ?? 'dark' }}" data-topbar-color="{{ $topbarColor ?? 'light' }}">

<head>
    @include('layouts.shared/title-meta', ['title' => $title])
    @yield('css')
    <script>
      // แบบกำหนดเองถาวร:
      sessionStorage.setItem('__CONFIG__', JSON.stringify({
          theme:'light',
          nav:'topnav',
          layout:{mode:'fluid',position:'fixed'},
          topbar:{color:'light'},
          menu:{color:'dark'},
          sidenav:{size:'default',user:false}
      }));
      // หรือจะใช้ removeItem('__CONFIG__') ถ้าอยากให้ยึดค่าจาก Blade
    </script> 

    @include('layouts.shared/head-css', ['mode' => $mode ?? '', 'demo' => $demo ?? ''])
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        @include('layouts.shared/topbar')
        @include('layouts.shared/left-sidebar')

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                    <br>
                   {{ $slot }}
                </div>
                <!-- container -->

            </div>
            <!-- content -->

            @include('layouts.shared/footer')
        </div>

    </div>
    <!-- END wrapper -->

    @yield('modal')

    @include('layouts.shared/right-sidebar')

    @include('layouts.shared/footer-scripts')

    @vite(['resources/js/layout.js', 'resources/js/main.js'])

</body>

</html>
