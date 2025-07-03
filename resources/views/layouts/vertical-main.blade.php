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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Use Bootstrap 5 JS bundle instead of Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Select2 Bootstrap Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <!-- Select2 ปรับแต่งพิเศษ -->
    <style>
        /* ให้ dropdown อยู่เหนือองค์ประกอบอื่นๆ */
        span.select2-dropdown {
            z-index: 9999;
        }
        
        /* แก้ปัญหาการแสดงผลใน input-group */
        .input-group > div[wire\:ignore] {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }
        
        /* ปรับแต่งรูปแบบเมื่อใช้ใน input-group */
        .input-group .select2-container--bootstrap-5 .select2-selection {
            height: 100%;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        
        /* ทำให้แสดงผลเต็มความกว้าง */
        .select2-container {
            width: 100% !important;
            display: block;
        }
    </style>
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
