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
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    {{-- Glightbox (LIBRARY) --}}
    @if (Request::is('product/*'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    @endif

    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (Request::is("receipt/*"))
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    @endif
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand text-bold" href="#">Lestari Motor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-2">
                    <li class="nav-item">
                        <a class="nav-link {{ setActiveLink('/') }}" aria-current="page" href="/">
                            <i class="ri-home-line"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ setActiveLink('cart') }}" href="/cart">
                            <i class="ri-shopping-cart-line"></i> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        @guest
                            <a class="nav-link" href="/login">
                                <i class="ri-login-circle-line"></i> Login
                            </a>
                        @endguest

                        @auth
                            <form action="/logout" method="POST">
                                @csrf
                                <button class="nav-link border-0 bg-transparent" type="submit">
                                    <i class="ri-logout-circle-r-line"></i> Logout
                                </button>
                            </form>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{ $content ?? null }}

    <!--   Bootstrap Files   -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>

    {{-- Core --}}
    <script src="{{ asset('js/soft-ui-dashboard.min.js') }}"></script>

    {{-- Glightbox (LIBRARY) --}}
    @if (Request::is('product/*'))
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    @endif

    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        /**
         * @author Karla Safa
         * Modul Manajemen Navigasi Active State
         *
         * Script ini menangani pewarnaan navigasi untuk tautan aktif
         * dengan menambahkan kelas warna primer pada elemen yang sedang aktif.
         *
         * Fitur Utama:
         * - Identifikasi elemen navigasi aktif
         * - Penambahan styling warna primer
         * - Dukungan multiple navigasi
         *
         * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
         */
        document.addEventListener("DOMContentLoaded", function () {

            // Seleksi semua elemen navigasi di halaman
            elements(".nav-link").forEach(function (element) {

                // Periksa apakah elemen memiliki kelas 'active'
                if(element.classList.contains("active")) {

                    // Tambahkan kelas warna primer untuk highlight
                    element.classList.add("text-primary")
                }
            });
        });
    </script>

    {{ $script ?? null }}
</body>
</html>
