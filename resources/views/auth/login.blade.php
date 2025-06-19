<x-auth-layout>
    <x-slot:content>
        <div class="card card-plain mt-8">

            {{-- Menampilkan alert jika ada session yang dikirim --}}
            <x-flash-message class="text-center"></x-flash-message>

            <div class="card-header pb-0 text-left bg-transparent">
                <h3 class="font-weight-bolder text-info text-gradient text-center">Login</h3>
                <p class="mb-0 text-center">Enter your email and password to sign in</p>
            </div>
            <div class="card-body">
                <form method="POST" role="form" action="/authenticate">
                    @csrf
                    <label>Email</label>
                    <div class="mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p class="text-sm">
                        Don't have an account?
                        <a href="register" class="text-info font-weight-bold">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </x-slot:content>
</x-auth-layout>
