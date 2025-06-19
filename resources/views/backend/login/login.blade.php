@extends('backend.layouts.app') <!-- Pastikan untuk menggunakan layout yang sesuai -->

@section('content')
<div class="container">
    <h2 class="text-center mb-4">Login</h2>

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-4"> <!-- Mengatur lebar kolom form -->
            <form action="{{ route('backend.login') }}" method="post" class="bg-light p-4 rounded shadow">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
