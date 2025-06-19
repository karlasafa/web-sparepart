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
                                    <h6 class="mb-0">Products</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a id="create-btn" href="/dashboard/products/create" class="btn btn-primary d-flex mb-0">
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
                                @include("dashboard.products.contents.table")
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk menampilkan box modal (detail produk) -->
            @include("dashboard.products.contents.detail")

            {{-- Modal untuk menampilkan box modal (cetak laporan) --}}
            <x-print-modal></x-print-modal>

            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * Script untuk manajemen produk dan interaksi modal detail produk
             *
             * @description Menangani event listener untuk detail produk, lightbox, dan laporan produk
             * @author Karla Safa
             */
            document.addEventListener("DOMContentLoaded", function() {

                // Inisialisasi lightbox global
                let lightbox = GLightbox();

                /**
                 * Event listener untuk menampilkan detail produk saat tombol detail diklik
                 * Mengambil data produk secara asynchronous dan memperbarui konten modal
                 */
                document.addEventListener("click", async function(event) {

                    // Cek apakah elemen yang diklik memiliki class 'btn-detail'
                    if (event.target.matches('.btn-detail')) {

                        // Ambil ID produk dari dataset
                        const productId = event.target.dataset.id;

                        // Fetch data produk dari server berdasarkan ID
                        const product = await fetchData(`/dashboard/products/${productId}`);

                        // Update konten modal dengan detail produk
                        element('#productTitle').innerText = product.title;
                        element('#productDescription').innerHTML = product.description;
                        element('#productPrice').innerText = rupiah(product.price);
                        element('#productWeight').innerText = product.weight;
                        element('#productStock').innerText = product.stock;

                        // Set status produk
                        element('#productStatus').innerText = product.status ? 'Published' : 'Blocked';

                        // Set gambar utama produk
                        element('#productImage').src = `{{ asset('storage/${product.image}') }}`;

                        // Tambahkan gambar tambahan produk ke gallery
                        product.images.forEach(function(image) {
                            element("#productImages").innerHTML += `
                                <div class="col-6">
                                    <img src="{{ asset('storage/${image.source}') }}" alt="" class="product-images glightbox">
                                </div>
                            `
                        })

                        // Reinisialisasi lightbox jika terdapat gambar
                        if (product.images.length) {
                            lightbox = GLightbox()
                        }
                    }
                });

                /**
                 * Event listener untuk mereset gallery gambar saat modal ditutup
                 * Mengosongkan kontainer gambar produk
                 */
                element("#productDetailModal").addEventListener('hidden.bs.modal', function() {
                    element("#productImages").innerHTML = ""
                });

                // Variabel untuk menyimpan rentang tanggal laporan
                let startDate = "",
                    endDate = ""

                /**
                 * Event listener untuk memilih tanggal awal laporan
                 * Memperbarui URL cetak laporan
                 */
                element("#startDate").addEventListener("change", function() {
                    startDate = this.value
                    element("#printBtn").href = `{{ url('dashboard/products/report/${startDate}/${endDate}') }}`
                })

                /**
                 * Event listener untuk memilih tanggal akhir laporan
                 * Memperbarui URL cetak laporan
                 */
                element("#endDate").addEventListener("change", function() {
                    endDate = this.value
                    element("#printBtn").href = `{{ url('dashboard/products/report/${startDate}/${endDate}') }}`
                })

                /**
                 * Event listener untuk mereset nilai variabel dan form laporan saat modal ditutup
                 */
                element("#printReportModal").addEventListener("hidden.bs.modal", function() {
                    startDate = ""
                    endDate = ""
                    element("#startDate").value = null
                    element("#endDate").value = null
                    element("#printBtn").removeAttribute("href")
                })
            });
        </script>

    </x-slot:script>
</x-dashboard-layout>
