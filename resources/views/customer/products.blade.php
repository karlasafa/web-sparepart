<x-customer-layout>
    <x-slot:content>
        <!-- Hero Section -->
        <x-header></x-header>

        <!-- Kontainer -->
        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <x-product-categories :data="$categories"></x-product-categories>

                    <section class="my-5" id="latest-products">
                        <h2 class="text-center">All Products</h2>
                        <x-product-card :data="$products"></x-product-card>
                    </section>
                </div>
            </div>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Modul Manajemen Penambahan Produk ke Keranjang
             *
             * Script ini menangani proses penambahan produk ke keranjang belanja
             * melalui mekanisme AJAX dengan notifikasi interaktif.
             *
             * Fitur Utama:
             * - Penambahan produk ke keranjang secara asynchronous
             * - Dukungan multiple tombol "Tambah ke Keranjang"
             * - Validasi respon server
             * - Notifikasi hasil menggunakan SweetAlert
             * - Logging data keranjang
             *
             * @requires fetchData - Fungsi custom untuk melakukan fetch request
             * @requires Swal - Library SweetAlert untuk notifikasi
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            document.addEventListener("DOMContentLoaded", function () {

                // Seleksi semua tombol "Tambah ke Keranjang" di halaman
                elements(".addToCartBtn").forEach(async function (btn) {

                    // Tambahkan event listener klik pada setiap tombol
                    btn.addEventListener("click", async function (event) {
                        try {
                            // Kirim permintaan AJAX untuk menambahkan produk ke keranjang
                            const response = await fetchData(`{{ url("cart/add") }}`, {
                                // Gunakan metode POST untuk menambahkan item
                                method: "POST",

                                // Konfigurasikan header request
                                headers: {
                                    // Tentukan tipe konten sebagai JSON
                                    "Content-Type": "application/json",

                                    // Sertakan CSRF token untuk keamanan
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },

                                // Kirim ID produk yang akan ditambahkan
                                body: JSON.stringify({ product: event.target.dataset.id })
                            });

                            // Parsing response (meskipun redundan dengan fetchData,
                            // tetap disertakan untuk fleksibilitas)
                            const data = await response;

                            // Periksa status respon dari server
                            if(data.status) {

                                // Tampilkan notifikasi sukses
                                Swal.fire({
                                    title: "Success!",
                                    text: data.message,
                                    icon: "success",

                                    // Kustomisasi warna tombol konfirmasi
                                    confirmButtonColor: "#cb0c9f"
                                });
                            } else {
                                // Tampilkan notifikasi gagal
                                Swal.fire({
                                    title: "Failed!",
                                    text: data.message,
                                    icon: "error",

                                    // Kustomisasi warna tombol konfirmasi
                                    confirmButtonColor: "#cb0c9f"
                                });
                            }

                            // Log data keranjang untuk debugging atau analitik
                            // CATATAN: Sebaiknya hilangkan atau batasi di production
                            console.log(data.cart);

                        } catch (error) {
                            // Tangani kesalahan yang mungkin terjadi selama proses
                            console.error("Kesalahan saat menambahkan produk ke keranjang:", error);

                            // Tampilkan notifikasi error generic
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan. Silakan coba lagi.",
                                icon: "error",
                                confirmButtonColor: "#cb0c9f"
                            });
                        }
                    });
                });
            });

            /**
             * Catatan Implementasi Teknis
             *
             * Alur Kerja:
             * 1. Tunggu DOM selesai dimuat
             * 2. Temukan semua tombol "Tambah ke Keranjang"
             * 3. Tambahkan event listener pada setiap tombol
             * 4. Kirim request AJAX untuk menambahkan produk
             * 5. Validasi respon server
             * 6. Tampilkan notifikasi sesuai hasil
             * 7. Log data keranjang (opsional)
             *
             * Karakteristik Kunci:
             * - Dukungan multiple tombol
             * - Penggunaan async/await
             * - Validasi respon server
             *
             * Pertimbangan Keamanan:
             * - Menggunakan CSRF token
             * - Validasi sisi server
             * - Penanganan error komprehensif
             *
             * Optimasi Potensial:
             * - Implementasi debounce pada tombol
             * - Disable tombol selama proses request
             * - Implementasi loading indicator
             *
             * Dependensi:
             * @external fetchData - Fungsi custom fetch
             * @external Swal - Library SweetAlert
             */

            /**
             * Catatan Debugging dan Pengembangan
             *
             * Variabel dan Properti Penting:
             * @property {Object} response - Respon mentah dari server
             * @property {Object} data - Data hasil parsing response
             * @property {boolean} data.status - Status keberhasilan operasi
             * @property {string} data.message - Pesan dari server
             * @property {Array|Object} data.cart - Informasi keranjang
             *
             * Contoh Struktur data.cart:
             * {
             *   items: [],         // Daftar item di keranjang
             *   total: 0,          // Total harga
             *   count: 0           // Jumlah item
             * }
             */
        </script>
    </x-slot:script>
</x-customer-layout>
