<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Menampilkan halaman beranda pelanggan.
     *
     * @description Menyiapkan dan menampilkan halaman utama dengan daftar kategori,
     * produk terbaru, dan produk terlaris untuk pengalaman pengguna.
     *
     * Fungsi ini menyiapkan data untuk ditampilkan di halaman utama, termasuk:
     * - Daftar kategori produk
     * - Produk terbaru (6 produk terbaru)
     * - Produk terlaris
     *
     * @return \Illuminate\View\View Tampilan halaman beranda dengan data produk.
     */
    public function index()
    {
        // Ambil semua kategori produk
        // Dapatkan 6 produk terbaru yang sudah dipublikasikan
        // Dapatkan produk terlaris menggunakan metode topProducts()
        return view("customer.home", [
            "categories" => Category::all(), // Ambil semua kategori
            "latestProducts" => Product::publishedData()->latest()->take(6)->get(), // 6 produk terbaru
            "bestSellingProducts" => $this->topProducts() // Produk terlaris
        ]);
    }

    /**
     * Menampilkan halaman daftar produk.
     *
     * @description Mengumpulkan dan menampilkan seluruh produk beserta kategorinya
     * dalam sebuah tampilan daftar produk komprehensif.
     *
     * Fungsi ini menyiapkan data untuk halaman produk, termasuk:
     * - Daftar kategori produk
     * - Semua produk dengan informasi kategori
     *
     * @return \Illuminate\View\View Tampilan halaman produk dengan daftar produk.
     */
    public function products()
    {
        // Ambil semua kategori dan produk beserta kategorinya
        return view("customer.products", [
            "categories" => Category::all(), // Ambil semua kategori
            "products" => Product::with(["category"])->get() // Ambil semua produk dengan relasi kategori
        ]);
    }

    /**
     * Menampilkan produk berdasarkan kategori tertentu.
     *
     * @description Memfilter dan menampilkan produk-produk yang termasuk dalam
     * kategori spesifik, memberikan pengalaman penelusuran produk yang terfokus.
     *
     * Fungsi ini menampilkan produk-produk yang termasuk dalam kategori spesifik:
     * - Memvalidasi keberadaan kategori
     * - Mengambil semua kategori untuk navigasi
     * - Mengambil produk-produk dalam kategori tertentu
     *
     * @param int $id Identifier dari kategori produk.
     *
     * @return \Illuminate\View\View Tampilan halaman produk kategori dengan daftar produk.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika kategori tidak ditemukan.
     */
    public function category(int $id)
    {
        // Cari kategori berdasarkan ID, lempar exception jika tidak ditemukan
        $category = Category::findOrFail($id);

        // Kembalikan view dengan data kategori dan produk dalam kategori tersebut
        return view("customer.product-category", [
            "categories" => Category::all(), // Ambil semua kategori untuk navigasi
            "category" => $category, // Kategori yang dipilih
            "products" => Product::where("category_id", $id) // Filter produk berdasarkan kategori
                                ->with(["category"]) // Sertakan relasi kategori
                                ->get() // Ambil semua produk
        ]);
    }

    /**
     * Menampilkan detail produk spesifik.
     *
     * @description Mengambil dan menampilkan informasi lengkap dari sebuah produk
     * termasuk kategori dan gambar terkait.
     *
     * Fungsi ini:
     * - Mencari produk berdasarkan ID
     * - Memuat relasi kategori dan gambar produk
     *
     * @param int $id Identifier unik dari produk yang akan ditampilkan.
     *
     * @return \Illuminate\View\View Tampilan halaman detail produk.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika produk tidak ditemukan.
     */
    public function product(int $id)
    {
        // Cari produk dengan relasi kategori dan gambar
        $product = Product::with(["category", "images"])->find($id);

        // Tampilkan view detail produk
        return view("customer.product", ["product" => $product]);
    }

    /**
     * Mengidentifikasi ID produk terlaris berdasarkan total kuantitas penjualan.
     *
     * @description Menghitung dan mengurutkan produk berdasarkan jumlah penjualan
     * untuk mengidentifikasi produk paling populer.
     *
     * Proses utama:
     * - Mengambil semua pesanan
     * - Menghitung total kuantitas setiap produk
     * - Mengurutkan produk berdasarkan jumlah penjualan
     * - Mengambil 6 produk teratas
     *
     * @return array Daftar ID produk terlaris.
     */
    public function getTopProductIds()
    {
        // Ambil semua pesanan dari database
        $orders = Order::all();

        // Inisialisasi array untuk menyimpan jumlah produk yang terjual
        $productCounts = [];

        // Iterasi setiap pesanan untuk menghitung kuantitas produk
        foreach ($orders as $order) {
            // Konversi produk ke format array jika masih dalam bentuk JSON
            if (is_string($order->products)) {
                $products = json_decode($order->products, true); // Dekode JSON menjadi array
            } else {
                $products = $order->products; // Gunakan langsung jika sudah array
            }

            // Hitung kuantitas untuk setiap produk dalam pesanan
            foreach ($products as $product) {
                // Ekstrak ID dan kuantitas produk
                $productId = $product["id"];
                $quantity = $product["quantity"];

                // Inisialisasi hitungan produk jika belum ada
                if (!isset($productCounts[$productId])) {
                    $productCounts[$productId] = 0;
                }

                // Akumulasi total kuantitas produk
                $productCounts[$productId] += $quantity;
            }
        }

        // Urutkan produk berdasarkan jumlah penjualan tertinggi
        arsort($productCounts);

        // Ambil 6 produk teratas
        $topProducts = array_slice($productCounts, 0, 6, true);

        // Ekstrak ID produk terlaris
        $topProductIds = array_keys($topProducts);

        // Kembalikan daftar ID produk terlaris
        return $topProductIds;
    }

    /**
     * Mengambil detail produk terlaris.
     *
     * @description Mengonversi ID produk terlaris menjadi objek produk lengkap
     * untuk ditampilkan di berbagai bagian aplikasi.
     *
     * Proses:
     * - Mendapatkan ID produk terlaris
     * - Mengambil detail produk berdasarkan ID
     *
     * @return \Illuminate\Database\Eloquent\Collection Kumpulan objek produk terlaris.
     */
    public function topProducts()
    {
        // Dapatkan ID produk terlaris
        $topProductIds = $this->getTopProductIds();

        // Ambil detail produk berdasarkan ID yang didapat
        $products = Product::whereIn("id", $topProductIds)->get();

        // Kembalikan kumpulan produk terlaris
        return $products;
    }

    /**
     * Menampilkan halaman keranjang belanja.
     *
     * @description Mengambil dan memproses data keranjang dari session,
     * melengkapi informasi produk dari database.
     *
     * Proses utama:
     * - Mengambil data keranjang dari session
     * - Memeriksa keranjang tidak kosong
     * - Mengambil detail produk dari database
     * - Menggabungkan informasi produk ke dalam keranjang
     *
     * @return \Illuminate\View\View|RedirectResponse
     * Tampilan halaman keranjang atau redirect jika keranjang kosong.
     */
    public function cart()
    {
        // Ambil data keranjang dari session dan konversi ke objek
        $cart = json_decode(collect(session("cart"))->toJson());

        // Redirect jika keranjang kosong
        if(!$cart) {
            return redirect("/")->with("empty-cart", "Cart is empty");
        }

        // Ekstrak ID produk dari keranjang
        $productIds = array_column($cart->products, "id");

        // Ambil detail produk dari database berdasarkan ID
        $products = Product::whereIn("id", $productIds)->get();

        // Perbarui informasi produk di keranjang dengan data dari database
        foreach ($products as $product) {
            // Temukan produk spesifik dalam keranjang
            $cartProduct = collect($cart->products)->firstWhere("id", $product->id);

            // Update informasi produk jika ditemukan
            if ($cartProduct) {
                $cartProduct->title = $product->title;
                $cartProduct->price = $product->price;
                $cartProduct->image = $product->image;
            }
        }

        // Konversi produk keranjang menjadi koleksi objek
        $cartProducts = collect($cart->products)->map(fn ($product) => (object) $product);

        // Update produk di keranjang
        $cart->products = $cartProducts;

        // Tampilkan halaman keranjang dengan data
        return view("customer.cart", ["cart" => $cart]);
    }

    /**
     * Menambahkan produk ke keranjang belanja.
     *
     * @description Memproses penambahan produk ke keranjang dengan validasi
     * dan manajemen kuantitas produk.
     *
     * Proses utama:
     * - Validasi input produk
     * - Memeriksa keberadaan produk
     * - Mengelola kuantitas produk dalam keranjang
     * - Menghitung total harga keranjang
     *
     * @param Request $request Objek request dengan data produk.
     *
     * @return \Illuminate\Http\JsonResponse Respons JSON status penambahan produk.
     */
    public function addToCart(Request $request)
    {
        // Validasi input produk
        $validator = Validator::make($request->all(), ["product" => "required|integer|exists:products,id"]);

        // Kembalikan error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->get("product")
            ]);
        }

        // Ambil data produk yang valid
        $postData = $validator->validated();

        // Cari produk di database
        $product = Product::find($postData["product"]);

        // Kembalikan error jika produk tidak ditemukan
        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product Not Found!"
            ]);
        }

        // Inisialisasi keranjang dengan struktur default jika belum ada
        $cart = session()->get("cart", [
            "products" => [],
            "payment" => [
                "method" => "Cash",
                "total" => 0, // Total awal
                "status" => "pending" // Status awal
            ]
        ]);

        // Cek apakah produk sudah ada di keranjang
        $productIndex = array_search($postData["product"], array_column($cart["products"], "id"));

        if ($productIndex !== false) {
            // Update kuantitas dan subtotal jika produk sudah ada
            $cart["products"][$productIndex]["quantity"] += 1;
            $cart["products"][$productIndex]["subtotal"] = $cart["products"][$productIndex]["quantity"] * $product->price;
        } else {
            // Tambahkan produk baru ke keranjang jika belum ada
            $cart["products"][] = [
                "id" => $product->id,
                "quantity" => 1,
                "subtotal" => $product->price
            ];
        }

        // Hitung ulang total harga keranjang
        $cart["payment"]["total"] = 0;
        foreach ($cart["products"] as $item) {
            $cart["payment"]["total"] += $item["subtotal"];
        }

        // Simpan keranjang ke session
        session()->put("cart", $cart);

        // Kembalikan respons sukses
        return response()->json([
            "status" => true,
            "message" => "Product successfully added to cart!",
            "cart" => session("cart")
        ]);
    }

    /**
     * Mengurangi kuantitas produk di keranjang belanja.
     *
     * @description Mengelola pengurangan kuantitas produk dalam keranjang,
     * dengan logika penghapusan produk jika kuantitas mencapai nol.
     *
     * Proses utama:
     * - Validasi input produk
     * - Mencari produk di keranjang
     * - Mengurangi kuantitas atau menghapus produk
     * - Memperbarui subtotal dan total keranjang
     *
     * @param Request $request Objek request dengan ID produk.
     *
     * @return \Illuminate\Http\JsonResponse Respons JSON status pengurangan kuantitas.
     */
    public function decreaseQuantity(Request $request)
    {
        // Validasi input produk
        $validator = Validator::make($request->all(), [
            "product" => "required|integer|exists:products,id"
        ]);

        // Kembalikan error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->get("product")
            ]);
        }

        // Ambil data produk yang valid
        $postData = $validator->validated();

        // Ambil data keranjang dari session
        $cart = session()->get("cart", ["products" => []]);

        // Cari index produk di keranjang
        $productIndex = array_search(
            $postData["product"],
            array_column($cart["products"], "id")
        );

        // Proses pengurangan kuantitas jika produk ditemukan
        if ($productIndex !== false) {
            // Kurangi kuantitas jika lebih dari 1
            if ($cart["products"][$productIndex]["quantity"] > 1) {
                $cart["products"][$productIndex]["quantity"] -= 1;

                // Ambil detail produk untuk perhitungan harga
                $product = Product::find($postData["product"]);

                // Perbarui subtotal produk
                $cart["products"][$productIndex]["subtotal"] =
                    $cart["products"][$productIndex]["quantity"] * $product->price;
            } else {
                // Hapus produk dari keranjang jika kuantitas adalah 1
                unset($cart["products"][$productIndex]);
                $cart["products"] = array_values($cart["products"]);
            }

            // Hitung ulang total keranjang
            $cart["payment"]["total"] = array_sum(
                array_column($cart["products"], "subtotal")
            );

            // Simpan perubahan ke session
            session()->put("cart", $cart);

            // Kembalikan respons sukses
            return response()->json([
                "status" => true,
                "message" => "Product quantity decreased",
                "cart" => session("cart")
            ]);
        }

        // Kembalikan error jika produk tidak ditemukan
        return response()->json([
            "status" => false,
            "message" => "Product not found in cart"
        ]);
    }

    /**
     * Menghapus produk dari keranjang belanja.
     *
     * @description Menangani proses penghapusan produk secara keseluruhan
     * dari keranjang belanja.
     *
     * Proses utama:
     * - Validasi input produk
     * - Mencari produk di keranjang
     * - Menghapus produk
     * - Memperbarui total keranjang
     *
     * @param Request $request Objek request dengan ID produk.
     *
     * @return \Illuminate\Http\JsonResponse Respons JSON status penghapusan produk.
     */
    public function removeFromCart(Request $request)
    {
        // Validasi input produk
        $validator = Validator::make($request->all(), [
            "product" => "required|integer|exists:products,id"
        ]);

        // Kembalikan error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => $validator->errors()->get("product")
            ]);
        }

        // Ambil data produk yang valid
        $postData = $validator->validated();

        // Ambil data keranjang dari session
        $cart = session()->get("cart", ["products" => []]);

        // Cari index produk di keranjang
        $productIndex = array_search(
            $postData["product"],
            array_column($cart["products"], "id")
        );

        // Proses penghapusan produk jika ditemukan
        if ($productIndex !== false) {
            // Hapus produk dari keranjang
            unset($cart["products"][$productIndex]);

            // Reset index array keranjang
            $cart["products"] = array_values($cart["products"]);

            // Hitung ulang total keranjang
            $cart["payment"]["total"] = array_sum(
                array_column($cart["products"], "subtotal")
            );

            // Simpan perubahan ke session
            session()->put("cart", $cart);

            // Kembalikan respons sukses
            return response()->json([
                "status" => true,
                "message" => "Product removed from cart",
                "cart" => session("cart")
            ]);
        }

        // Kembalikan error jika produk tidak ditemukan
        return response()->json([
            "status" => false,
            "message" => "Product not found in cart"
        ]);
    }

    /**
     * Mengosongkan keranjang belanja.
     *
     * @description Menghapus seluruh data keranjang dari session pengguna.
     *
     * Proses utama:
     * - Menghapus data keranjang dari session
     * - Mengarahkan pengguna ke halaman utama
     *
     * @return \Illuminate\Http\RedirectResponse Redirect ke halaman utama.
     */
    public function clearCart()
    {
        // Hapus seluruh data keranjang dari session
        session()->forget("cart");

        // Redirect ke halaman utama
        return redirect("/");
    }

    /**
     * Memproses checkout pesanan.
     *
     * @description Menangani proses checkout dengan validasi input,
     * penyimpanan pesanan, dan pembersihan keranjang.
     *
     * Proses utama:
     * - Validasi input checkout
     * - Memeriksa keranjang tidak kosong
     * - Membuat record pesanan baru
     * - Menyimpan detail pesanan ke database
     * - Menghapus keranjang setelah checkout
     *
     * @param Request $request Objek request dengan data checkout.
     *
     * @return \Illuminate\Http\JsonResponse Respons JSON status checkout.
     *
     * @throws \Exception Potensi error saat penyimpanan pesanan.
     */
    public function checkout(Request $request)
    {
        // Validasi input checkout
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        // Kembalikan error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        // Ambil data yang sudah divalidasi
        $validatedData = $validator->validated();

        // Ambil data keranjang dari session
        $cart = json_decode(collect(session("cart"))->toJson());

        // Periksa apakah keranjang kosong
        if (!$cart || empty($cart->products)) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty.'
            ]);
        }

        // Ambil ID customer dari pengguna yang sedang login
        $customerId = Auth::user()->id;

        // Inisiasi model pesanan baru
        $order = new Order();

        try {
            // Simpan detail pesanan ke database
            $order->products = json_encode($cart->products); // Simpan produk sebagai JSON
            $order->payment_method = $validatedData['payment_method'];
            $order->total = $cart->payment->total; // Total dari keranjang
            $order->status = 'pending'; // Status default pesanan
            $order->address = $validatedData['address']; // Alamat pengiriman
            $order->customer = $customerId; // ID customer
            $order->save();

            // Hapus keranjang setelah checkout berhasil
            session()->forget('cart');

            // Kembalikan respons sukses
            return response()->json([
                'status' => true,
                'message' => 'Checkout successful!',
                'order' => $order
            ]);
        } catch (\Exception $e) {
            // Tangani error yang mungkin terjadi saat penyimpanan
            return response()->json([
                'status' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menampilkan struk/kwitansi pesanan.
     *
     * @description Mengambil detail pesanan dan menampilkan halaman struk.
     *
     * Proses utama:
     * - Mencari pesanan berdasarkan ID
     * - Menampilkan halaman struk dengan detail pesanan
     *
     * @param int $orderId Identifier unik pesanan.
     *
     * @return \Illuminate\View\View Tampilan halaman struk pesanan.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika pesanan tidak ditemukan.
     */
    public function receipt($orderId)
    {
        // Cari pesanan berdasarkan ID
        $order = Order::findOrFail($orderId);

        // Tampilkan halaman struk dengan detail pesanan
        return view("customer.receipt", ["order" => $order]);
    }
}
