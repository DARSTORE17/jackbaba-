<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bravus Market | Customer Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/customerHeader.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customerSidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/customerLayout.css') }}" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @yield('styles')
</head>

<body>
    <!-- HEADER -->
    @include('components.customerHeader')

    <!-- SIDEBAR -->
    @include('components.customerSidebar')

    <!-- MAIN CONTENT -->
    <div class="pb-5">
        @yield('content')
    </div>

    <!-- FOOTER -->
   <footer class="fixed-bottom text-center text-muted small py-3 bg-white border-top px-3" style="margin-left: 190px;">
        <span>© 2026 Bravus Market | Customer Portal V 1.0 | Developed by HN</span>
    </footer>

    <style>
        @media (max-width: 768px) {
            footer {
                margin-left: 0 !important;
            }
            .pb-5 {
                padding-bottom: 5rem !important;
            }
        }
        footer {
            width: calc(100% - 190px);
        }
        @media (max-width: 768px) {
            footer {
                width: 100% !important;
            }
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sellerSidebarToggler.js') }}"></script>

    <!-- Custom Scripts -->
    @yield('scripts')

    <!-- SweetAlert Messages -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
