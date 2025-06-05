<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"> --}}
    <!-- logo -->
    <link rel="icon" href="{{ asset('images/gen-logo.jpeg') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Custom responsive styles for sidebar toggle -->
    <style>
      :root{
          --logo-color: #FA5508;
          --primary-color: #007bff;
          --secondary-color: #6c757d;
          --success-color: #28a745;
          --danger-color: #dc3545;
          --warning-color: #ffc107;
          --info-color: #17a2b8;
          --light-color: #f8f9fa;
          --dark-color: #343a40;
        }

        .content-wrapper {
            transition: margin-left 0.3s ease-in-out;
        }
        
        .container, .card, .account-card, .form-container {
            width: 100% !important;
            max-width: 100% !important;
            transition: width 0.3s ease-in-out;
        }
        
        @media (max-width: 991.98px) {
            .content-wrapper {
                margin-left: 0 !important;
            }
        }

        /* horizontal navbar */
        /* Horizontal Navbar Styles */
     .horizontal-navbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .horizontal-navbar a {
        margin: 3px 5px;
        padding: 3px 5px ;
        color:rgb(255, 255, 255);
        background-color: var(--logo-color);
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
    }
    .horizontal-navbar a:hover{
      background-color: var(--danger-color);
      color: white;
    }
    </style>
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <link  href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('images/logo.jpeg') }}" alt="AdminLTELogo" height="100" width="100" style="color: var(--logo-color);">
        </div>

        <!-- Navbar -->
        @include('partials.navbar') <!-- Extracted Navbar into a partial -->

        <!-- Main Sidebar Container -->
        @include('partials.sidebar') <!-- Extracted Sidebar into a partial -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content" style="margin-top: 50px;">
                @yield('content') <!-- This is where the child content will be loaded -->
            </section>
            <!-- /.content -->
        </div>
    </div>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<!-- Custom script for sidebar toggle responsiveness -->
<script>
    $(document).ready(function() {
        // Fix nested content-wrapper divs on page load
        fixNestedContentWrappers();
        
        // Handle sidebar toggle button click
        $('[data-widget="pushmenu"]').on('click', function() {
            setTimeout(function() {
                // Force layout recalculation
                $('.content-wrapper').css('transition', 'none');
                $('.content-wrapper')[0].offsetHeight; // Force reflow
                $('.content-wrapper').css('transition', 'margin-left 0.3s ease-in-out');
                
                // Adjust any containers inside
                $('.container, .card, .account-card, .form-container').css('width', '100%');
                
                // Fix nested content-wrapper divs again after toggle
                fixNestedContentWrappers();
            }, 50);
        });
        
        // Function to fix nested content-wrapper divs
        function fixNestedContentWrappers() {
            // Find all content-wrapper divs inside the main content-wrapper
            $('.content-wrapper .content-wrapper').each(function() {
                // Replace the class with container-fluid to prevent nesting issues
                $(this).removeClass('content-wrapper').addClass('container-fluid');
            });
            
            // Ensure all tables are responsive
            $('.table').addClass('table-responsive');
            
            // Fix any right-shifted content
            $('.content-wrapper > div').css('max-width', '100%');
        }
    });
    
</script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{  asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{  asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{  asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{  asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{  asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{  asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{  asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{  asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{  asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{  asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{  asset('dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>



<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->

<!-- Popper.js (required for dropdowns, tooltips, popovers) -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap 4 JS -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->



<!-- Bootstrap JS (must be after jQuery) -->


<!-- Bootstrap live local miss match -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>




    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap 4 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    
    <!-- Custom scripts from child views -->
    @stack('scripts')
    
    <!-- Debug script -->
    <script>
        console.log('Main layout scripts loaded');
        console.log('jQuery version:', $.fn.jquery);
        console.log('Bootstrap version:', $.fn.tooltip ? 'Loaded' : 'Not loaded');
        console.log('SweetAlert2 version:', typeof Swal !== 'undefined' ? 'Loaded' : 'Not loaded');
    </script>
</body>
</html>
