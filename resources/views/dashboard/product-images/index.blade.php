<x-dashboard-layout>
    <x-slot:content>
        <div class="container-fluid py-4">
            <div class="row my-4">
                <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">

                            {{-- Menampilkan alert jika ada session yang dikirim --}}
                            <x-flash-message></x-flash-message>

                            <div class="row align-items-center mb-3">
                                <div class="col-lg-6 col-7">
                                    <h6 class="mb-0">Product Images</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <a id="create-btn" href="/dashboard/product-images/create" class="btn btn-primary d-flex ms-auto mb-0">
                                        <i class="ri-add-large-line"></i>
                                        <span class="ps-1">Create New</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                @include("dashboard.product-images.contents.table")
                            </div>
                        </div>
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
             * Modul Inisialisasi Lightbox
             *
             * Script ini menangani:
             * - Inisialisasi lightbox global
             * - Aktivasi pratinjau media interaktif
             *
             * @requires GLightbox - Library untuk membuat lightbox responsif
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            document.addEventListener("DOMContentLoaded", function () {
                /**
                 * Inisialisasi GLightbox
                 * Mengonfigurasi lightbox untuk semua elemen media di halaman
                 *
                 * @type {GLightbox} - Instance lightbox global
                 * @property {Object} options - Konfigurasi default GLightbox
                 */
                const lightbox = GLightbox();
            });
        </script>
    </x-slot:script>
</x-dashboard-layout>
