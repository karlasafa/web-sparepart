<x-dashboard-layout>
    <x:slot:content>
        <div class="container-fluid">
            <div class="page-header min-height-300 border-radius-xl mt-4 profile-header">
                <span class="mask bg-gradient-primary opacity-6"></span>
            </div>
            <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src='{{ asset("storage/$user->picture") }}' alt="" class="w-100 border-radius-lg shadow-sm glightbox">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="mb-0 font-weight-bold text-sm">{{ $user->role }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-md-8 d-flex align-items-center">
                                    <h6 class="mb-0">Profile Information</h6>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a class="btn btn-primary" href="/dashboard/profile/edit">
                                        <i class="ri-user-settings-line"></i>
                                        <span class="ps-1">Edit</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group">
                                <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                                    <strong class="text-dark">Name:</strong>
                                    <span class="ps-2">{{ $user->name }}</span>
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Email:</strong>
                                    <span class="ps-2">{{ $user->email }}</span>
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark">Phone:</strong>
                                    <span class="ps-2">{{ $user->phone }}</span>
                                </li>
                                <li class="list-group-item border-0 ps-0 text-sm">
                                    <strong class="text-dark pe-2">Status:</strong>
                                    <span class="badge badge-sm {{ $user->status ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                        {{ $user->status ? 'Active' : 'Not Active' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-dashboard-footer></x-dashboard-footer>
    </x:slot:content>
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
