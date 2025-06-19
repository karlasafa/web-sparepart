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
                        <h2 class="text-center">Latest Products</h2>
                        <x-product-card :data="$latestProducts"></x-product-card>
                    </section>

                    <section class="my-5" id="best-selling-products">
                        <h2 class="text-center">Best Selling Products</h2>–
                        <x-product-card :data="$bestSellingProducts"></x-product-card>
                    </section>
                </div>
            </div>
        </div>
    </x-slot:content>≠
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Menangani Proses Tambah Produk ke Keranjang secara Asynchronous
             *
             * Script ini mengimplementasikan fungsionalitas:
             * - Menambahkan event listener pada tombol "Tambah ke Keranjang"
             * - Mengirim permintaan AJAX untuk menambahkan produk ke keranjang
             * - Menampilkan notifikasi hasil operasi menggunakan SweetAlert
             * - Navigasi kondisional setelah penambahan produk
             *
             * @requires fetchData - Fungsi kustom untuk melakukan fetch request
             * @requires Swal - Library SweetAlert untuk notifikasi interaktif
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya sebelum menjalankan script
             */
            document.addEventListener("DOMContentLoaded", function () {

                // Pilih semua tombol "Tambah ke Keranjang" di halaman
                elements(".addToCartBtn").forEach(function (btn) {

                    // Tambahkan event listener klik pada setiap tombol
                    btn.addEventListener("click", async function (event) {

                        // Kirim permintaan AJAX untuk menambahkan produk ke keranjang
                        const data = await fetchData(`{{ url("cart/add") }}`, {
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

                        // Periksa status respon dari server
                        if(data.status) {

                            // Jika penambahan produk berhasil
                            const result = await Swal.fire({
                                // Konfigurasi modal sukses
                                title: "Success!",
                                text: data.message,
                                icon: "success",

                                // Tampilkan tombol "Batal" sebagai opsi menuju keranjang
                                showCancelButton: true,
                                cancelButtonColor: "#8392ab",
                                cancelButtonText: "Go to Cart",

                                // Konfigurasi warna tombol konfirmasi
                                confirmButtonColor: "#cb0c9f",
                            });

                            // Navigasi ke halaman keranjang jika tombol "Batal" ditekan
                            if (!result.isConfirmed) {
                                window.location.href = "{{ url('cart') }}"
                            }
                        } else {

                            // Jika penambahan produk gagal
                            Swal.fire({
                                // Konfigurasi modal error
                                title: "Failed!",
                                text: data.message,
                                icon: "error",

                                // Konfigurasi warna tombol
                                confirmButtonColor: "#cb0c9f"
                            });
                        }
                    });
                });
            });

            /**
             * Catatan Implementasi:
             *
             * 1. Menggunakan event delegation untuk menangani multiple tombol
             * 2. Implementasi asynchronous dengan async/await
             * 3. Integrasi SweetAlert untuk pengalaman notifikasi yang lebih baik
             * 4. Keamanan dengan penggunaan CSRF token
             *
             * Dependensi:
             * - fetchData(): Fungsi custom untuk melakukan fetch request
             * - Swal: Library SweetAlert untuk modal interaktif
             *
             * Alur Kerja:
             * 1. Tunggu DOM dimuat
             * 2. Temukan semua tombol "Tambah ke Keranjang"
             * 3. Tambahkan event listener
             * 4. Kirim request AJAX saat tombol diklik
             * 5. Tampilkan notifikasi berdasarkan hasil
             * 6. Navigasi opsional ke halaman keranjang
             */
        </script>
    </x-slot:script>
</x-customer-layout>
