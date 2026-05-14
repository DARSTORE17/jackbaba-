<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $systemSettings['site_name'] ?? config('app.name', 'Bravus Market'))</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- SweetAlert2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- bootsrap 5 icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- Custom CSS --}}
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('components.system-colors')

    <style>
        .hero-section {
            margin-top: 0;
            padding-top: 0;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #25D366;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.18);
            z-index: 9999;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .whatsapp-float:hover {
            transform: translateY(-3px);
            box-shadow: 0 22px 45px rgba(0, 0, 0, 0.22);
        }

        .whatsapp-float i {
            font-size: 1.4rem;
        }

        [v-cloak] {
            display: none;
        }

        .vue-page-shell {
            min-height: 100vh;
        }

        .vue-page-loader {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 88px;
            pointer-events: none;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0));
        }

        .vue-page-loader__bar {
            width: min(280px, calc(100vw - 48px));
            height: 4px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.12);
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.14);
        }

        .vue-page-loader__bar::after {
            content: "";
            display: block;
            width: 42%;
            height: 100%;
            border-radius: inherit;
            background: var(--primary-color, #0d6efd);
            animation: vueLoaderSlide 0.8s ease-in-out infinite;
        }

        .vue-page-content {
            transition: opacity 0.18s ease;
        }

        .vue-page-shell.is-loading .vue-page-content {
            opacity: 0.92;
        }

        @keyframes vueLoaderSlide {
            0% {
                transform: translateX(-110%);
            }

            100% {
                transform: translateX(260%);
            }
        }
    </style>
    {{-- Extra CSS from pages --}}
    <!-- vue-page-head-start -->
    @yield('css')
    <!-- vue-page-head-end -->
</head>

<body>
<div id="vue-layout-shell" class="vue-page-shell">
    <div id="vue-navigation-status">
        <div class="vue-page-loader" v-show="loading" v-cloak aria-live="polite" aria-label="Loading page">
            <div class="vue-page-loader__bar"></div>
        </div>
    </div>

    {{-- Header Component --}}
    @include('components.header')

    {{-- Main Content --}}
    <div id="vue-page-content" class="vue-page-content" data-vue-page>
        @yield('content')
    </div>


    {{-- Footer Component --}}
    @include('components.footer')

    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $systemSettings['whatsapp'] ?? '255754321987') }}?text=Hello%2C%20I%20want%20to%20order%20a%20product" class="whatsapp-float" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-whatsapp"></i>
    </a>
</div>

    {{-- Bootstrap JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('js/header.js') }}"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="{{ asset('js/vue-layout.js') }}"></script>

    @auth
    {{-- Auto Logout on Inactivity --}}
    <script>
        let inactivityTime = function () {
            let time;
            let countdownInterval;
            let remainingTime = 300; // 5 minutes in seconds

            console.log('Auto-logout script initialized. Timer will start on first activity.');
            window.onload = resetTimer;
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onmousedown = resetTimer;
            document.ontouchstart = resetTimer;
            document.onclick = resetTimer;
            document.onkeydown = resetTimer;
            document.addEventListener('scroll', resetTimer, true);

            function logout() {
                clearInterval(countdownInterval);
                console.log('Logging out due to inactivity...');
                // Submit the logout form
                document.getElementById('logout-form').submit();
            }

            function resetTimer() {
                clearTimeout(time);
                clearInterval(countdownInterval);
                remainingTime = 300;
                time = setTimeout(logout, 300000); // 5 minutes = 300000 ms
                console.log('Activity detected. Logout timer reset to 5 minutes.');

                countdownInterval = setInterval(() => {
                    remainingTime--;
                    if (remainingTime <= 0) {
                        clearInterval(countdownInterval);
                    } else if (remainingTime % 60 === 0 || remainingTime <= 10) { // Log every minute or last 10 seconds
                        console.log(`Time remaining to auto-logout: ${Math.floor(remainingTime / 60)}:${(remainingTime % 60).toString().padStart(2, '0')} minutes`);
                    }
                }, 1000);
            }
        };
        inactivityTime();
    </script>

    {{-- Hidden Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    @endauth

    {{-- Extra JS from pages --}}
    <div id="vue-page-scripts" data-vue-page-scripts hidden>
        @yield('scripts')
    </div>

</body>

</html>
