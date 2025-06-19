<x-auth-layout>
    <x-slot:content>
        <div class="card card-plain mt-8">

            {{-- Menampilkan alert jika ada session yang dikirim --}}
            <x-flash-message></x-flash-message>

            <div class="card-header pb-0 text-left bg-transparent">
                <h3 class="font-weight-bolder text-info text-gradient text-center">Register</h3>
                <p class="mb-0 text-center">Create your account by filling out the form below</p>
            </div>
            <div class="card-body">
                <form method="POST" role="form" action="/registration">
                    @csrf

                    <label>Name</label>
                    <div class="mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label>Email</label>
                    <div class="mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label>Phone Number</label>
                    <div class="mb-3">
                        <input type="number" class="form-control @error('phone') is-invalid @enderror" placeholder="Phone Number" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label>Password</label>
                    <div class="mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <label>Confirm Password</label>
                    <div class="mb-3">
                        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Register</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <p class="text-sm">
                        Already have an account?
                        <a href="login" class="text-info font-weight-bold">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </x-slot:content>
</x-auth-layout>
