<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bravus Market Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Seller/Shared CSS -->
    <link href="{{ asset('css/sellerHeader.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sellerSidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sellerLayout.css') }}" rel="stylesheet">

    @include('components.system-colors')

    <style>
        .navbar,
        .offcanvas {
            border-radius: var(--radius-md) !important;
            border: 1px solid rgba(15, 23, 42, 0.06) !important;
            background-color: var(--surface-color) !important;
            box-shadow: var(--shadow-soft) !important;
        }

        .card {
            overflow: hidden;
        }

        .text-success,
        .text-warning,
        .text-danger {
            color: inherit !important;
        }

        .border-start.border-4 {
            border-left-width: 0.5rem !important;
        }

        .table thead th {
            background: rgba(255, 255, 255, 0.92);
        }
    </style>

    @yield('styles')
</head>

<body>
    @include('components.adminHeader')
    @include('components.adminSidebar')

    <div class="pb-5">
        @yield('content')
    </div>

    <footer class="fixed-bottom text-center text-muted small py-3 bg-white border-top px-3" style="margin-left: 190px;">
        <span>© 2026 Bravus Market | Admin Control Panel</span>
    </footer>

    <style>
        @media (max-width: 768px) {
            footer { margin-left: 0 !important; }
            .pb-5 { padding-bottom: 5rem !important; }
        }
        footer { width: calc(100% - 190px); }
        @media (max-width: 768px) { footer { width: 100% !important; } }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sellerSidebarToggler.js') }}"></script>

    @yield('scripts')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
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
                text: "{{ session('error') }}",
                showConfirmButton: true
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
