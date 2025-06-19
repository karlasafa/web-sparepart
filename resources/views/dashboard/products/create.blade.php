<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">

                {{-- Input untuk menyimpan lokasi redirect setelah muncul alert --}}
                <input type="hidden" id="index-page" value="{{ url('dashboard/products') }}">

                {{-- Menampilkan alert jika ada session yang dikirim --}}
                <x-flash-message class="px-3"></x-flash-message>

                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h6>Create Product</h6>
                        </div>
                        <form id="create-product" class="card-body px-4 pt-0 pb-2" action="/dashboard/products" method="POST" enctype="multipart/form-data">
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
                            <div class="mb-3">
                                <label for="category_id" class="form-label">
                                    <span>Category</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">
                                    <span>Price</span>
                                    <span class="text-danger pe-1">*</span>
                                    <span class="text-secondary" id="price-text"></span>
                                </label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="weight" class="form-label">
                                    <span>Weight</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" required>
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="stock" class="form-label">
                                    <span>Stock</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <span>Description</span>
                                    <span class="text-danger">*</span>
                                </label>
                                <div id="editor" style="height: 300px;"></div>
                                <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <x-input-image name="image"></x-input-image>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" {{ session('alert-success') ? 'disabled' : '' }}>
                                    <i class="ri-save-line"></i>
                                    <span class="ps-1">Save</span>
                                </button>
                                <a href="/dashboard/products" class="btn btn-dark">
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
             * @author Karla Safa
             * Modul Manajemen Formulir Produk
             *
             * Script ini menangani:
             * - Konversi harga ke format rupiah
             * - Inisialisasi editor teks Quill
             * - Pengolahan deskripsi produk
             *
             * @requires Quill - Library editor teks kaya
             * @requires rupiah - Fungsi konversi mata uang
             * @requires preview - Fungsi pratinjau elemen
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            const handlePreview = (event) => preview(event.target);

            document.addEventListener("DOMContentLoaded", function () {
                /**
                 * Handler konversi harga ke teks rupiah
                 * Memperbarui tampilan harga dalam format mata uang
                 *
                 * @event keyup - Dipicu saat input harga berubah
                 * @param {Event} event - Event perubahan input
                 */
                element("#price").addEventListener("keyup", function () {
                    if(this.value) {
                        element("#price-text").innerHTML = `(${rupiah(this.value)})`
                    } else {
                        element("#price-text").innerHTML = ""
                    }
                });

                /**
                 * Inisialisasi editor teks Quill
                 * Mengonfigurasi toolbar dan fitur editing
                 *
                 * @type {Quill} - Instance editor Quill
                 * @property {Object} options - Konfigurasi editor
                 */
                const quill = new Quill('#editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, false] }],
                            ['bold', 'italic', 'underline'],
                            ['link'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            ['clean'] // remove formatting button
                        ]
                    }
                });

                /**
                 * Handler submit formulir produk
                 * Menyimpan konten deskripsi dari Quill ke input tersembunyi
                 *
                 * @event submit - Dipicu saat formulir akan dikirim
                 */
                element("#create-product").addEventListener("submit", function () {
                    // Salin konten HTML dari Quill ke input tersembunyi
                    element("input[name=description]").value = quill.root.innerHTML;
                });

                /**
                 * Inisialisasi konten editor dengan data lama
                 * Mempertahankan input pengguna setelah validasi gagal
                 */
                quill.root.innerHTML = `{!! old('description') !!}`;
            });
        </script>
    </x-slot:script>
</x-dashboard-layout>
