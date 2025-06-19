<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">

                {{-- Input untuk menyimpan lokasi redirect setelah muncul alert --}}
                <input type="hidden" id="index-page" value="{{ url('dashboard/categories') }}">

                {{-- Menampilkan alert jika ada session yang dikirim --}}
                <x-flash-message class="px-3"></x-flash-message>

                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h6>Create Category</h6>
                        </div>
                        <form class="card-body px-4 pt-0 pb-2" action="/dashboard/categories" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <span>Title</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" {{ session('alert-success') ? 'disabled' : '' }}>
                                    <i class="ri-save-line"></i>
                                    <span class="ps-1">Save</span>
                                </button>
                                <a href="/dashboard/categories" class="btn btn-dark">
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
</x-dashboard-layout>
