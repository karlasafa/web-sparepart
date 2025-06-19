<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">

                {{-- Input untuk menyimpan lokasi redirect setelah muncul alert --}}
                <input type="hidden" id="index-page" value="{{ url('dashboard/product-images') }}">

                {{-- Menampilkan alert jika ada session yang dikirim --}}
                <x-flash-message class="px-3"></x-flash-message>

                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-2">
                            <h6>Add Product Image</h6>
                        </div>
                        <form class="card-body px-4 pt-0 pb-2" action="/dashboard/product-images" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex flex-column mb-3">
                                <label for="product_id" class="form-label">Product</label>
                                <select name="product_id" id="product_id" class="searchable-select">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ "($product->id) $product->title" }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <x-input-image name="source"></x-input-image>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" {{ session('alert-success') ? 'disabled' : '' }}>
                                    <i class="ri-save-line"></i>
                                    <span class="ps-1">Save</span>
                                </button>
                                <a href="/dashboard/product-images" class="btn btn-dark">
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
             * Modul Inisialisasi Halaman
             *
             * Script ini menangani:
             * - Konfigurasi preview dinamis
             * - Inisialisasi select dengan fitur pencarian
             *
             * @requires NiceSelect - Library untuk select dengan fitur pencarian
             * @requires preview - Fungsi custom untuk pratinjau elemen
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            const handlePreview = (event) => preview(event.target);

            document.addEventListener("DOMContentLoaded", function () {
                /**
                 * Inisialisasi select dengan fitur pencarian
                 * Menggunakan library NiceSelect untuk meningkatkan fungsionalitas select
                 *
                 * @function
                 * @name initSearchableSelect
                 * @param {string} selector - Selektor untuk elemen select
                 * @param {Object} [options] - Konfigurasi opsional NiceSelect
                 */
                NiceSelect.bind(element(".searchable-select"), {
                    searchable: true
                });
            });
        </script>
    </x-slot:script>
</x-dashboard-layout>
