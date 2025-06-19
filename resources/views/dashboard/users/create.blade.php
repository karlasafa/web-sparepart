<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">

                {{-- Input untuk menyimpan lokasi redirect setelah muncul alert --}}
                <input type="hidden" id="index-page" value="{{ url('dashboard/users') }}">

                {{-- Menampilkan alert jika ada session yang dikirim --}}
                <x-flash-message class="px-3"></x-flash-message>

                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h6>Create User</h6>
                        </div>
                        <form class="card-body px-4 pt-0 pb-2" action="/dashboard/users" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <span>Name</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <span>Email</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <span>Role</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="role" id="role" class="form-select">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <span>Password</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <span>Phone Number</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}"required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <x-input-image name="picture"></x-input-image>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" {{ session('alert-success') ? 'disabled' : '' }}>
                                    <i class="ri-save-line"></i>
                                    <span class="ps-1">Save</span>
                                </button>
                                <a href="/dashboard/users" class="btn btn-dark">
                                    <i class="ri-arrow-left-line"></i>
                                    <span class="ps-1">Back</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author
             * Handler untuk pratinjau elemen
             * Menggunakan fungsi preview dengan elemen target
             *
             * @param {Event} event - Event yang memicu pratinjau
             * @returns {void}
             */
            const handlePreview = (event) => preview(event.target);
        </script>
    </x-slot:script>
</x-dashboard-layout>
