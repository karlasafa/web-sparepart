<x-customer-layout>
    <x-slot:content>
        <style>
            img {
                width: 100px;
                aspect-ratio: 1 / 1;
                object-fit: cover;
            }

            .payment-methods {
                display: flex;
                align-items: center;
            }

            .payment-methods input[type="radio"] {
                display: none;
            }

            .payment-methods label {
                display: flex;
                align-items: center;
                cursor: pointer;
                padding: 10px;
                border: 1px solid #ddd;
                margin-right: 10px;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .payment-methods input[type="radio"]:checked + label {
                color: white;
                background-color: #cb0c9f;
            }

            .payment-methods i {
                font-size: 24px;
                margin-right: 5px;
            }

            .payment-methods span {
                font-size: 16px;
            }
        </style>

        <div class="container my-5">
            <form class="card border" id="checkout-form">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Cart</h3>
                        <a href="cart/clear">
                            <i class="ri-delete-bin-line pe-1"></i> Remove All
                        </a>
                    </div>
                    <div class="d-flex flex-column gap-3">
                        @foreach ($cart->products as $product)
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img class="img-fluid rounded" src='{{ asset("storage/$product->image") }}' alt="">
                                    <div>
                                        <h5>{{ $product->title }}</h5>
                                        <h6 class="subtotal" data-id="{{ $product->id }}">{{ rupiah($product->subtotal) }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="badge bg-dark text-white border-0 decreaseQuantityBtn" data-id="{{ $product->id }}">
                                        <i class="ri-subtract-line" style="pointer-events: none"></i>
                                    </button>
                                    <span class="badge border text-dark quantity" data-id="{{ $product->id }}">{{ $product->quantity }}</span>
                                    <button type="button" class="badge bg-dark text-white border-0 increaseQuantityBtn" data-id="{{ $product->id }}">
                                        <i class="ri-add-line" style="pointer-events: none"></i>
                                    </button>
                                    <a class="badge bg-danger text-white removeProductBtn" data-id="{{ $product->id }}" href="#">
                                        <i class="ri-delete-bin-line" style="pointer-events: none"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" rows="5" class="form-control"></textarea>
                        </div>

                        <label class="form-label">Payment Methods</label>
                        <div class="payment-methods">
                            <input type="radio" id="payment-cash" name="payment_method" value="Cash" checked>
                            <label for="payment-cash">
                                <i class="ri-cash-line"></i>
                                <span>Cash</span>
                            </label>

                            <input type="radio" id="payment-debit" name="payment_method" value="Debit">
                            <label for="payment-debit">
                                <i class="ri-bank-card-line"></i>
                                <span>Debit</span>
                            </label>

                            <input type="radio" id="payment-qris" name="payment_method" value="QRIS">
                            <label for="payment-qris">
                                <i class="ri-qr-code-line"></i>
                                <span>QRIS</span>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h6 class="mb-0">Total:</h6>
                        <div class="d-flex align-items-center gap-2">
                            <h6 class="mb-0 pe-2 paymentTotal">{{ rupiah($cart->payment->total) }}</h6>
                            <button type="submit" class="btn btn-primary mb-0 checkoutBtn">Checkout</button>
                            <a class="btn btn-secondary mb-0" href="/">Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </x-slot:content>
    <x-slot:script>
        <script>
            /**
             * @author Karla Safa
             * Event listener untuk inisialisasi fungsionalitas halaman keranjang
             *
             * @description Menangani interaksi pengguna dengan keranjang belanja:
             * - Mengurangi/menambah kuantitas produk
             * - Menghapus produk dari keranjang
             * - Proses checkout
             */
            document.addEventListener("DOMContentLoaded", function () {
                /**
                 * Mengurangi kuantitas produk di keranjang
                 *
                 * @param {string} productId - ID produk yang akan dikurangi kuantitasnya
                 * @returns {Promise<void>} - Promise yang diselesaikan setelah proses pengurangan
                 */
                async function decreaseQuantity(productId) {
                    // Kirim permintaan pengurangan kuantitas ke server
                    const response = await fetchData(`{{ url("cart/subtract") }}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ product: productId })
                    });

                    // Parse response dari server
                    const data = await response;

                    // Ambil elemen kuantitas produk
                    const quantity = element(`.quantity[data-id="${productId}"]`);

                    // Proses respon dari server
                    if (data.status) {
                        // Kurangi kuantitas di tampilan
                        quantity.innerText = parseInt(quantity.innerText) - 1

                        // Reload halaman jika kuantitas 0
                        if(!parseInt(quantity.innerText)) {
                            window.location.reload()
                        }

                        // Perbarui total pembayaran
                        element(".paymentTotal").innerText = rupiah(data.cart.payment.total)
                    } else {
                        // Tampilkan pesan error jika gagal
                        Swal.fire({
                            title: "Failed!",
                            text: data.message,
                            icon: "error",
                            confirmButtonColor: "#cb0c9f"
                        });
                    }
                }

                /**
                 * Menambah kuantitas produk di keranjang
                 *
                 * @param {string} productId - ID produk yang akan ditambah kuantitasnya
                 * @returns {Promise<void>} - Promise yang diselesaikan setelah proses penambahan
                 */
                async function increaseQuantity(productId) {
                    // Kirim permintaan penambahan kuantitas ke server
                    const response = await fetchData(`{{ url("cart/add") }}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ product: productId })
                    });

                    // Parse response dari server
                    const data = await response;

                    // Ambil elemen kuantitas produk
                    const quantity = element(`.quantity[data-id="${productId}"]`);

                    // Proses respon dari server
                    if (data.status) {
                        // Tambah kuantitas di tampilan
                        quantity.innerText = parseInt(quantity.innerText) + 1

                        // Perbarui total pembayaran
                        element(".paymentTotal").innerText = rupiah(data.cart.payment.total)
                    } else {
                        // Tampilkan pesan error jika gagal
                        Swal.fire({
                            title: "Failed!",
                            text: data.message,
                            icon: "error",
                            confirmButtonColor: "#cb0c9f"
                        });
                    }

                    // Log data keranjang untuk debugging
                    console.log(JSON.stringify(data.cart))
                }

                /**
                 * Menghapus produk dari keranjang
                 *
                 * @param {string} productId - ID produk yang akan dihapus
                 * @returns {Promise<void>} - Promise yang diselesaikan setelah proses penghapusan
                 */
                async function removeProduct(productId) {
                    // Kirim permintaan penghapusan produk ke server
                    const response = await fetchData(`{{ url("cart/remove") }}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ product: productId })
                    });

                    // Parse response dari server
                    const data = await response;

                    // Proses respon dari server
                    if (data.status) {
                        // Reload halaman setelah berhasil menghapus
                        window.location.reload()
                    } else {
                        // Tampilkan pesan error jika gagal
                        Swal.fire({
                            title: "Failed!",
                            text: data.message,
                            icon: "error",
                            confirmButtonColor: "#cb0c9f"
                        });
                    }
                }

                // Tambahkan event listener untuk tombol kurangi kuantitas
                document.querySelectorAll(".decreaseQuantityBtn").forEach(function (btn) {
                    btn.addEventListener("click", function () {
                        // Ambil ID produk dari data attribute
                        const productId = btn.dataset.id;
                        // Panggil fungsi pengurangan kuantitas
                        decreaseQuantity(productId);
                    });
                });

                // Tambahkan event listener untuk tombol tambah kuantitas
                document.querySelectorAll(".increaseQuantityBtn").forEach(function (btn) {
                    btn.addEventListener("click", function () {
                        // Ambil ID produk dari data attribute
                        const productId = btn.dataset.id;
                        // Panggil fungsi penambahan kuantitas
                        increaseQuantity(productId);
                    });
                });

                // Tambahkan event listener untuk tombol hapus produk
                document.querySelectorAll(".removeProductBtn").forEach(function (btn) {
                    btn.addEventListener("click", function (event) {
                        // Cegah aksi default (navigasi)
                        event.preventDefault();

                        // Ambil ID produk dari data attribute
                        const productId = btn.dataset.id;

                        // Panggil fungsi penghapusan produk
                        removeProduct(productId);
                    });
                });

                /**
                 * Proses checkout
                 *
                 * @description Menangani proses checkout dengan validasi dan pengiriman data
                 */
                element(".checkoutBtn").addEventListener("click", async function (e) {
                    // Cegah aksi default form
                    e.preventDefault();

                    // Ambil data dari form
                    const address = document.getElementById('address').value;
                    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

                    try {
                        // Kirim data checkout ke server
                        const data = await fetchData('/checkout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                address: address,
                                payment_method: paymentMethod
                            })
                        });

                        // Proses respon checkout
                        if (data.status) {
                            // Nonaktifkan tombol untuk mencegah multi-klik
                            e.target.disabled = true

                            // Redirect ke halaman receipt
                            window.location.href = `{{ url('receipt/${data.order.id}') }}`;
                        } else {
                            // Tangani error checkout
                            let errorMessage = '';

                            // Periksa tipe pesan error
                            if (typeof data.message === 'object') {
                                // Gabungkan pesan error dari validasi
                                Object.values(data.message).forEach(errors => {
                                    errorMessage += errors.join('\n') + '\n';
                                });
                            } else {
                                errorMessage = data.message;
                            }
                            // Tampilkan pesan error
                            alert('Error: ' + errorMessage);
                        }
                    } catch (error) {
                        // Tangani error jika terjadi kesalahan saat checkout
                        console.error('Checkout error:', error);
                        alert('Terjadi kesalahan saat memproses checkout: ' + error.message);
                    }
                });
            });
        </script>
    </x-slot:script>
</x-customer-layout>
