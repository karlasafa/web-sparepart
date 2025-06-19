<x-customer-layout>
    <x-slot:content>
        <style>
            .debit-card {
                width: 350px;
                height: 220px;
                background: linear-gradient(135deg, #0068b4, #00a3e1);
                border-radius: 15px;
                color: white;
                padding: 20px;
                position: relative;
                overflow: hidden;
            }

            .card-logo {
                position: absolute;
                top: 20px;
                right: 20px;
                width: 60px;
            }

            .chip-container {
                display: flex;
                align-items: center;
                margin-top: 20px;
            }

            .card-chip {
                width: 50px;
                margin-right: 15px;
            }

            .card-number {
                font-size: 1.3rem;
                letter-spacing: 3px;
                margin-top: 20px;
            }

            .card-details {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .card-holder {
                text-transform: uppercase;
            }
        </style>
        <div class="container my-5">
            <div class="col-lg-6 mx-auto">
                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <h3 class="mb-0">
                                <i class="ri-receipt-line pe-1"></i> Receipt
                            </h3>
                            <div class="d-flex align-items-center gap-2 btnGroup">
                                <button type="button" class="btn btn-primary mb-0 printBtn">
                                    <i class="ri-printer-line pe-1"></i> Print
                                </button>
                                <a href="/" class="btn btn-secondary mb-0">
                                    <i class="ri-home-line pe-1"></i> Back to Home
                                </a>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0">
                                    <i class="ri-file-list-3-line"></i> Order ID:
                                </h6>
                                <p class="mb-0 ms-2">{{ $order->id }}</p>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0">
                                    <i class="ri-user-line"></i> Customer:
                                </h6>
                                <p class="mb-0 ms-2">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0">
                                    <i class="ri-bank-card-line"></i> Payment Method:
                                </h6>
                                <p class="mb-0 ms-2 text-capitalize">{{ $order->payment_method }}</p>
                            </div>
                            <div class="d-flex align-items-center mb-1">
                                <h6 class="mb-0">
                                    <i class="ri-information-line"></i> Payment Status:
                                </h6>
                                <p class="mb-0 ms-2 text-capitalize">
                                    @if ($order->status === "pending")
                                        <span class="badge bg-gradient-secondary">{{ $order->status }}</span>
                                    @elseif ($order->status === "success")
                                        <span class="badge bg-gradient-success">{{ $order->status }}</span>
                                    @else
                                        <span class="badge bg-gradient-danger">{{ $order->status }}</span>
                                    @endif
                                </p>
                            </div>

                            @if (strtolower($order->payment_method) === "qris")
                                <div class="mt-3 text-center">
                                    <img src="{{ asset('img/qr-code.png') }}" alt="" class="img-fluid">
                                </div>
                            @elseif (strtolower($order->payment_method) === "debit")
                                <div class="debit-card mx-auto mt-3">
                                    <img src="{{ asset('img/bca-logo.png') }}" alt="BCA Logo" class="card-logo">
                                    <div class="chip-container">
                                        <img src="{{ asset('img/chip.png') }}" alt="Card Chip" class="card-chip">
                                    </div>
                                    <div class="card-number">1234 5678 9012 3456</div>
                                    <div class="card-details">
                                        <div class="card-holder">Karla Safa</div>
                                        <div class="card-expiry">12/25</div>
                                    </div>
                                </div>
                            @endif

                            <div class="alert alert-info text-white text-center mt-3">
                                Payment Total: {{ rupiah($order->total) }}
                            </div>
                            <p class="text-bold text-center">
                                Thank you for shopping at <span class="text-primary">Lestari Motor</span>.
                                <span class="d-block">Your payment successfully processed</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Menangani proses screenshot dan download halaman
             *
             * @description Fungsi ini melakukan capture halaman, menyembunyikan tombol selama proses,
             * dan mengunduh hasil screenshot sebagai file PNG
             *
             * @param {Event} event - Event klik tombol print
             * @returns {void}
             */
            element(".printBtn").addEventListener("click", async function () {
                try {
                    // Sembunyikan tombol-tombol selama proses screenshot
                    element(".btnGroup").classList.add("d-none");

                    // Lakukan capture halaman dengan pengaturan khusus
                    const canvas = await html2canvas(element(".card-body"), {
                        // Izinkan penggunaan gambar dari sumber berbeda
                        allowTaint: true,

                        // Aktifkan Cross-Origin Resource Sharing
                        useCORS: true,

                        // Tingkatkan kualitas resolusi screenshot
                        scale: 2
                    });

                    // Buat elemen link untuk download
                    const link = document.createElement("a");

                    // Atur nama file download dengan ID pesanan
                    link.download = "receipt-{{ $order->id }}.png";

                    // Konversi canvas ke URL data
                    link.href = canvas.toDataURL();

                    // Simulasikan klik untuk memulai download
                    link.click();

                } catch (error) {
                    // Tangkap dan log error jika proses screenshot gagal
                    console.error("Error capturing screenshot:", error);

                    // Tampilkan pesan kesalahan kepada pengguna
                    alert("Gagal membuat screenshot");
                } finally {
                    // Kembalikan tampilan tombol ke keadaan semula
                    // Blok ini akan selalu dijalankan, baik proses berhasil atau gagal
                    element(".btnGroup").classList.remove("d-none");
                }
            });
        </script>
    </x-slot:script>
</x-customer-layout>
