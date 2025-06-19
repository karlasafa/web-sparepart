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
                                    <h6 class="mb-0">Orders</h6>
                                </div>
                                <div class="col-lg-6 col-5 my-auto text-end">
                                    <a href="#" class="btn btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#printReportModal">
                                        <i class="ri-printer-line"></i>
                                        <span class="ps-1">Report</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                @include("dashboard.orders.contents.table")
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal untuk menampilkan box modal (detail produk) -->
            @include("dashboard.orders.contents.detail")

            {{-- Modal untuk menampilkan box modal (cetak laporan) --}}
            <x-print-modal></x-print-modal>

            <x-dashboard-footer></x-dashboard-footer>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Modul Manajemen Detail Pesanan dan Laporan
             *
             * Script ini menangani:
             * - Tampilan detail pesanan dinamis
             * - Pemilihan rentang tanggal laporan
             * - Interaksi modal untuk detail pesanan dan laporan
             *
             * @requires fetchData - Fungsi custom untuk mengambil data dari server
             * @requires Bootstrap Modal - Untuk manajemen modal
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            document.addEventListener("DOMContentLoaded", function () {
                /**
                 * Handler untuk menampilkan detail pesanan secara dinamis
                 *
                 * Fitur:
                 * - Mengambil data pesanan berdasarkan ID
                 * - Memperbarui konten modal dengan informasi pesanan
                 * - Menampilkan status pembayaran dengan pewarnaan kondisional
                 * - Menampilkan daftar produk dalam pesanan
                 *
                 * @param {Event} event - Event klik pada dokumen
                 */
                document.addEventListener("click", async function (event) {

                    // Periksa apakah elemen yang diklik adalah tombol detail pesanan
                    if (event.target.matches('.btn-detail')) {
                        try {
                            // Ekstrak ID pesanan dari dataset elemen
                            const orderId = event.target.dataset.id;

                            // Ambil data pesanan dari server
                            const order = await fetchData(`/dashboard/orders/${orderId}`);

                            // Debug: Log data pesanan yang diterima
                            console.log(order);

                            // Perbarui elemen modal dengan informasi pesanan
                            element("#orderId").innerText = order.id;
                            element("#customerId").innerText = order.customer.id;
                            element("#customerAddress").innerText = order.customer.address;
                            element("#paymentTotal").innerText = order.payment.total;
                            element("#paymentMethod").innerText = order.payment.method;
                            element("#paymentStatus").innerText = order.payment.status;

                            // Terapkan styling status pembayaran
                            const paymentStatusElement = element("#paymentStatus");
                            paymentStatusElement.className = ""; // Reset kelas
                            paymentStatusElement.classList.add("badge", "badge-sm");

                            // Tentukan warna badge berdasarkan status pembayaran
                            switch (order.payment.status.toLowerCase()) {
                                case "success":
                                    paymentStatusElement.classList.add("bg-gradient-success");
                                    break;
                                case "pending":
                                    paymentStatusElement.classList.add("bg-gradient-secondary");
                                    break;
                                default:
                                    paymentStatusElement.classList.add("bg-gradient-danger");
                            }

                            // Reset kontainer produk
                            const productsContainer = element("#products");
                            productsContainer.innerHTML = "";

                            // Render daftar produk dalam pesanan
                            order.products.forEach(function (product) {
                                productsContainer.innerHTML += `
                                    <div class="col-12">
                                        <div class="card mb-3 shadow-none border">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <img src="{{ asset('storage/${product.image}') }}"
                                                        class="img-fluid rounded-start"
                                                        style="aspect-ratio:1/1;object-fit:cover;width:100%;">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3">${product.title}</h5>
                                                        <p class="card-text mb-1">
                                                            <strong>Subtotal: </strong>
                                                            <span>${product.subtotal}</span>
                                                        </p>
                                                        <p class="card-text mb-0">
                                                            <strong>Quantity: </strong>
                                                            <span>${product.quantity}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        } catch (error) {
                            // Tangani kesalahan saat mengambil data
                            console.error("Gagal mengambil detail pesanan:", error);
                            // Opsional: Tampilkan pesan error kepada pengguna
                        }
                    }
                });

                /**
                 * Reset modal detail pesanan saat ditutup
                 *
                 * Membersihkan:
                 * - Kelas status pembayaran
                 * - Daftar produk
                 */
                element("#orderDetailModal").addEventListener('hidden.bs.modal', function() {
                    element("#paymentStatus").className = "";
                    element("#products").innerHTML = "";
                });

                // Variabel untuk menyimpan rentang tanggal laporan
                let startDate = "",
                    endDate = "";

                /**
                 * Handler untuk memilih tanggal awal laporan
                 *
                 * Memperbarui URL cetak laporan dengan tanggal yang dipilih
                 */
                element("#startDate").addEventListener("change", function() {
                    startDate = this.value;
                    updateReportPrintUrl(startDate, endDate);
                });

                /**
                 * Handler untuk memilih tanggal akhir laporan
                 *
                 * Memperbarui URL cetak laporan dengan tanggal yang dipilih
                 */
                element("#endDate").addEventListener("change", function () {
                    endDate = this.value;
                    updateReportPrintUrl(startDate, endDate);
                });

                /**
                 * Memperbarui URL cetak laporan berdasarkan rentang tanggal
                 *
                 * @param {string} start - Tanggal mulai
                 * @param {string} end - Tanggal akhir
                 */
                function updateReportPrintUrl(start, end) {
                    element("#printBtn").href = `{{ url('dashboard/orders/report/${start}/${end}') }}`;
                }

                /**
                 * Reset modal laporan saat ditutup
                 *
                 * Membersihkan:
                 * - Variabel tanggal
                 * - Input tanggal
                 * - URL cetak
                 */
                element("#printReportModal").addEventListener("hidden.bs.modal", function() {
                    startDate = "";
                    endDate = "";
                    element("#startDate").value = null;
                    element("#endDate").value = null;
                    element("#printBtn").removeAttribute("href");
                });
            });

            /**
             * Catatan Implementasi
             *
             * Alur Kerja:
             * 1. Inisialisasi event listener saat DOM dimuat
             * 2. Tangani klik tombol detail pesanan
             * 3. Ambil data pesanan dari server
             * 4. Perbarui modal dengan informasi pesanan
             * 5. Atur styling status pembayaran
             * 6. Render daftar produk
             * 7. Kelola modal laporan dengan pemilihan tanggal
             *
             * Pertimbangan:
             * - Penanganan error
             * - Pemisahan logika
             * - Fleksibilitas
             */
        </script>
    </x-slot:script>
</x-dashboard-layout>
