@extends('backend.layouts.app')

@section('content')
<div class="container">
    <nav class="mb-4">
        <a href="{{ route('backend.home') }}" class="btn btn-primary">Home</a>
        <a href="#" class="btn btn-secondary">User</a>
        <!-- Tambahkan tombol Logout yang memicu pengiriman form -->
        <button class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">Logout</button>
    </nav>

    <h3 class="mt-4">{{ $judul }}</h3>
    <p>
        Halo, <b>{{ Auth::user()->name }}</b> pada aplikasi Toko Online dengan hak akses yang anda miliki sebagai
        <b>
            @if (Auth::user()->role == 1)
                Super Admin
            @elseif (Auth::user()->role == 0)
                Admin
            @endif
        </b>
        ini adalah halaman utama dari aplikasi ini.
    </p>

    <!-- Form logout yang disembunyikan -->
    <form id="keluar-app" action="{{ route('backend.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    <!-- keluarAppEnd -->
</div>
@endsection

