<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            .sidebar {
                margin-left: -250px;
            }
            .sidebar-open .sidebar {
                margin-left: 0;
            }
            .content-wrapper {
                margin-left: 0 !important;
            }
            .main-sidebar, .main-sidebar:before {
                box-shadow: none !important;
                margin-left: 0;
                transform: translate(-250px, 0);
            }
            .sidebar-open .main-sidebar, 
            .sidebar-open .main-sidebar:before {
                transform: translate(0, 0);
            }
            .main-sidebar {
                transition: transform 0.3s ease-in-out;
                z-index: 1038;
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
            <section class="content" style="margin-top: 56px; padding: 5px 0 20px 0;">
                @yield('content') <!-- This is where the child content will be loaded -->
            </section>
            <!-- /.content -->
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    
    <!-- Custom script for sidebar toggle responsiveness -->
    <script>
        $(document).ready(function() {
            // Fix for nested content-wrapper divs
            function fixNestedContentWrappers() {
                $('.content-wrapper .content-wrapper').each(function() {
                    var $this = $(this);
                    var parentContent = $this.parent();
                    $this.children().appendTo(parentContent);
                    $this.remove();
                });
            }
            
            // Handle window resize
            function handleResize() {
                if ($(window).width() > 991.98) {
                    $('body').removeClass('sidebar-open');
                    $('.main-sidebar').css('transform', 'none');
                } else {
                    $('.main-sidebar').css('transform', 'translate(-250px, 0)');
                }
            }
            
            // Initial fix
            fixNestedContentWrappers();
            
            // Handle sidebar toggle
            $('[data-widget="pushmenu"]').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-open');
                
                if ($(window).width() <= 991.98) {
                    if ($('body').hasClass('sidebar-open')) {
                        $('.main-sidebar').css('transform', 'translate(0, 0)');
                    } else {
                        $('.main-sidebar').css('transform', 'translate(-250px, 0)');
                    }
                }
                
                // Force reflow
                setTimeout(function() {
                    $('.content-wrapper')[0].offsetHeight; // Force reflow
                    $('.content-wrapper').css('transition', 'margin-left 0.3s ease-in-out');
                    
                    // Adjust any containers inside
                    $('.container, .card, .account-card, .form-container').css('width', '100%');
                    
                    // Fix nested content-wrapper divs again after toggle
                    fixNestedContentWrappers();
                }, 50);
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 991.98) {
                    if (!$(e.target).closest('.main-sidebar').length && 
                        !$(e.target).closest('[data-widget="pushmenu"]').length) {
                        $('body').removeClass('sidebar-open');
                        $('.main-sidebar').css('transform', 'translate(-250px, 0)');
                    }
                }
            });
            
            // Prevent closing when clicking inside the sidebar
            $('.main-sidebar').on('click', function(e) {
                e.stopPropagation();
            });
            
            // Handle window resize
            $(window).on('resize', handleResize);
            
            // Initialize all tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Initialize all popovers
            $('[data-toggle="popover"]').popover();
        });
    </script>
    
    <!-- SweetAlert2 for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    
    <!-- Custom scripts from child views -->
    @stack('scripts')
    
    <!-- Debug script -->
    <script>
        $(document).ready(function() {
            console.log('Main layout scripts loaded');
            console.log('jQuery version:', $.fn.jquery);
            console.log('Bootstrap version:', $.fn.tooltip ? 'Loaded' : 'Not loaded');
            console.log('AdminLTE version:', typeof $.AdminLTE !== 'undefined' ? 'Loaded' : 'Not loaded');
            
            // Initialize push menu
            $('[data-widget="pushmenu"]').on('click', function() {
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
</body>
</html>
