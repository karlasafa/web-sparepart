<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Karla Safa">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/icon_univ_bsi.png') }}">
    <title>{{ config("app.name") }}</title>

    {{-- Google Fonts (LIBRARY) --}}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    {{-- Nucleo Icons (LIBRARY) --}}
    <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet" />

    {{-- Main CSS --}}
    <link id="pagestyle" href="{{ asset('css/soft-ui-dashboard.min.css') }}" rel="stylesheet" />

    {{-- Remix Icons (LIBRARY) --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />

    {{-- Datatables (LIBRARY) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.css">

    {{-- Quill (LIBRARY) --}}
    @if (Request::is('dashboard/products/*'))
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endif

    {{-- Select2 (LIBRARY) --}}
    @if (Request::is('dashboard/product-images/*'))
        <link rel="stylesheet" href="{{ asset('css/libs/nice-select2.css') }}">
    @endif

    {{-- Glightbox (LIBRARY) --}}
    @if (Request::is('dashboard/users') || Request::is('dashboard/products') || Request::is('dashboard/product-images') || Request::is("dashboard/profile"))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    @endif

    {{-- Custom Styling --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="g-sidenav-show  bg-gray-100">

    {{-- Sidebar --}}
    <x-dashboard-sidebar></x-dashboard-sidebar>
    {{-- End Sidebar --}}

    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">

        <!-- Navbar -->
        <x-dashboard-navbar></x-dashboard-navbar>
        <!-- End Navbar -->

        {{-- Content --}}
        {{ $content }}
        {{-- End Content --}}
    </main>

    <!--   Bootstrap Files   -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>

    {{-- Perfect Scrollbar and Smooth Scrollbar (LIBRARY) --}}
    <script src="{{ asset('js/libs/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/libs/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    {{-- Core --}}
    <script src="{{ asset('js/soft-ui-dashboard.min.js') }}"></script>

    {{-- DataTables (LIBRARY) --}}
    <script src="https://cdn.jsdelivr.net/npm/vanilla-datatables@latest/dist/vanilla-dataTables.min.js"></script>

    {{-- Quill (LIBRARY) --}}
    @if (Request::is('dashboard/products/*'))
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @endif

    {{-- Select2 (LIBRARY) --}}
    @if (Request::is('dashboard/product-images/*'))
        <script src="{{ asset('js/libs/nice-select2.js') }}"></script>
    @endif

    {{-- Glightbox (LIBRARY) --}}
    @if (Request::is('dashboard/users') || Request::is('dashboard/products') || Request::is('dashboard/product-images') || Request::is("dashboard/profile"))
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    @endif

    {{-- Script Tambahan --}}
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>

    {{ $script ?? null }}
</body>

</html>
