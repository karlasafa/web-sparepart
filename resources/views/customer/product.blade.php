<x-customer-layout>
    <x-slot:content>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="row gap-3">
                        <div class="col-12">
                            <img src='{{ asset("storage/$product->image") }}' class="img-fluid rounded glightbox w-100">
                        </div>
                        <div class="col-12">
                            <div class="row g-3">
                                @foreach ($product->images as $index => $image)
                                    <div class="col-3 {{ $index >= 4 ? 'd-none' : '' }}">
                                        <img src='{{ asset("storage/$image->source") }}' class="img-fluid rounded glightbox gallery" style="aspect-ratio: 1 / 1;object-fit:cover">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2>{{ $product->title }}</h2>
                    <p class="text-muted">{{ $product->category->title }}</p>
                    <p class="lead text-bold">{{ rupiah($product->price) }}</p>
                    <div>
                        {!! $product->description !!}
                    </div>
                    <p class="mb-1">Berat: {{ $product->weight }} kg</p>
                    <p class="mb-0">Stok: {{ $product->stock }} item</p>
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="btn btn-primary addToCartBtn" data-id="{{ $product->id }}">
                            <i class="ri-shopping-cart-line pe-1"></i> Add to cart
                        </button>
                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-s-line pe-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Modul Manajemen Keranjang dan Lightbox
             *
             * Script ini mengimplementasikan fungsionalitas:
             * - Inisialisasi Lightbox untuk gallery/preview gambar
             * - Penambahan produk ke keranjang melalui AJAX
             * - Notifikasi interaktif dengan navigasi kondisional
             *
             * Fitur Utama:
             * - Inisialisasi GLightbox
             * - Penambahan produk ke keranjang
             * - Notifikasi hasil menggunakan SweetAlert
             * - Navigasi otomatis ke halaman keranjang
             *
             * @requires GLightbox - Library untuk lightbox gallery
             * @requires fetchData - Fungsi custom untuk fetch request
             * @requires Swal - Library SweetAlert untuk notifikasi
             *
             * @event DOMContentLoaded - Memastikan DOM telah dimuat sepenuhnya
             */
            document.addEventListener("DOMContentLoaded", function () {
                // Inisialisasi GLightbox untuk gallery/preview gambar
                // Menggunakan konfigurasi default
                const lightbox = GLightbox();

                // Pilih tombol "Tambah ke Keranjang" di halaman
                element(".addToCartBtn").addEventListener("click", async function (event) {
                    try {
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
                            // Menggunakan 'this' untuk merujuk pada elemen yang di-klik
                            body: JSON.stringify({ product: this.dataset.id })
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
                                title: "Error!",
                                text: data.message,
                                icon: "error",

                                // Konfigurasi warna tombol
                                confirmButtonColor: "#cb0c9f"
                            });
                        }
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

            /**
             * Catatan Implementasi Teknis
             *
             * Alur Kerja:
             * 1. Inisialisasi GLightbox
             * 2. Tambahkan event listener pada tombol "Tambah ke Keranjang"
             * 3. Kirim request AJAX untuk menambahkan produk
             * 4. Validasi respon server
             * 5. Tampilkan notifikasi sesuai hasil
             * 6. Navigasi opsional ke halaman keranjang
             *
             * Perbedaan Kunci:
             * - Penggunaan 'this' untuk merujuk elemen
             * - Inisialisasi GLightbox
             * - Satu tombol "Tambah ke Keranjang"
             *
             * Pertimbangan Keamanan:
             * - Menggunakan CSRF token
             * - Validasi sisi server
             * - Penanganan error komprehensif
             *
             * Optimasi Potensial:
             * - Konfigurasi GLightbox disesuaikan
             * - Implementasi debounce
             * - Tambahkan loading state
             *
             * Dependensi:
             * @external GLightbox - Library lightbox
             * @external fetchData - Fungsi custom fetch
             * @external Swal - Library SweetAlert
             */
        </script>
    </x-slot:script>
</x-customer-layout>
