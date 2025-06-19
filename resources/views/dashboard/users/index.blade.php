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
                                    <h6 class="mb-0">Users</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a id="create-btn" href="/dashboard/users/create" class="btn btn-primary d-flex mb-0">
                                            <i class="ri-add-large-line"></i>
                                            <span class="ps-1">Create New</span>
                                        </a>
                                        <a href="#" class="btn btn-dark d-flex mb-0" data-bs-toggle="modal" data-bs-target="#printReportModal">
                                            <i class="ri-printer-line"></i>
                                            <span class="ps-1">Report</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                @include("dashboard.users.contents.table")
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal untuk menampilkan box modal (cetak laporan) --}}
            <x-print-modal></x-print-modal>

            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Modul Manajemen Laporan Pengguna
             *
             * Script ini menangani:
             * - Inisialisasi lightbox
             * - Pemilihan rentang tanggal laporan
             * - Pembuatan URL cetak laporan dinamis
             *
             * @requires GLightbox - Library untuk lightbox
             * @requires Bootstrap Modal - Untuk manajemen modal
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            document.addEventListener("DOMContentLoaded", function () {
                /**
                 * Inisialisasi lightbox
                 * Mengonfigurasi tampilan galeri gambar
                 *
                 * @type {GLightbox} - Instance lightbox
                 */
                const lightbox = GLightbox();

                /**
                 * Variabel penyimpan rentang tanggal laporan
                 *
                 * @type {Object} - Objek penyimpan tanggal
                 * @property {string} startDate - Tanggal mulai laporan
                 * @property {string} endDate - Tanggal akhir laporan
                 */
                let startDate = "",
                    endDate = ""

                /**
                 * Handler pemilihan tanggal awal laporan
                 * Memperbarui URL cetak laporan berdasarkan rentang tanggal
                 *
                 * @event change - Dipicu saat tanggal awal berubah
                 * @param {Event} event - Event perubahan input tanggal
                 */
                element("#startDate").addEventListener("change", function() {
                    startDate = this.value
                    element("#printBtn").href = `{{ url('dashboard/users/report/${startDate}/${endDate}') }}`
                })

                /**
                 * Handler pemilihan tanggal akhir laporan
                 * Memperbarui URL cetak laporan berdasarkan rentang tanggal
                 *
                 * @event change - Dipicu saat tanggal akhir berubah
                 * @param {Event} event - Event perubahan input tanggal
                 */
                element("#endDate").addEventListener("change", function() {
                    endDate = this.value
                    element("#printBtn").href = `{{ url('dashboard/users/report/${startDate}/${endDate}') }}`
                })

                /**
                 * Handler reset modal laporan
                 * Mereset nilai variabel dan form laporan saat modal ditutup
                 *
                 * @event hidden.bs.modal - Dipicu saat modal Bootstrap ditutup
                 * @param {Event} event - Event penutupan modal
                 */
                element("#printReportModal").addEventListener("hidden.bs.modal", function() {
                    // Reset variabel tanggal
                    startDate = ""
                    endDate = ""

                    // Reset input tanggal
                    element("#startDate").value = null
                    element("#endDate").value = null

                    // Hapus URL cetak
                    element("#printBtn").removeAttribute("href")
                })
            })
        </script>
    </x-slot:script>
</x-dashboard-layout>
