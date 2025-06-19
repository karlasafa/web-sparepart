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
                            <h6>Edit User</h6>
                        </div>
                        <form class="card-body px-4 pt-0 pb-2" action="/dashboard/users/{{ $user->id }}" method="POST" enctype="multipart/form-data">
                            @method("PUT")
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <span>Name</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <span>Email</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">
                                    <span>Role</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ ($user->role == $role || old('role') == $role) ? 'selected' : '' }}>
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
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <span>Phone Number</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ $user->phone }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <x-input-image name="picture" previous="{{ $user->picture }}"></x-input-image>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                @foreach ($statuses as $status)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status-{{ $status }}" value="{{ $status }}" {{ $status === $user->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status-{{ $status }}">
                                            {{ $status ? 'Active' : 'Not Active' }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" {{ session('alert-success') ? 'disabled' : '' }}>
                                    <i class="ri-save-line"></i>
                                    <span class="ps-1">Update</span>
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
