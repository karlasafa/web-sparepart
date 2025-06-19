<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DashboardOrderController extends Controller
{
    /**
     * Menampilkan daftar seluruh pesanan.
     *
     * @description Method ini mengambil semua data pesanan dari database dan
     *              menampilkannya dalam view dashboard orders.
     *
     * @return \Illuminate\View\View Halaman daftar pesanan dengan data orders.
     */
    public function index()
    {
        return view("dashboard.orders.index", [
            "orders" => Order::all() // Kirim data orders ke view untuk ditampilkan
        ]);
    }

    /**
     * Menampilkan detail pesanan berdasarkan ID.
     *
     * @description Method ini mengambil detail pesanan, termasuk produk, pelanggan,
     *              dan informasi pembayaran untuk sebuah pesanan spesifik.
     *
     * @param int $id Identifikasi unik pesanan yang akan ditampilkan.
     * @return \Illuminate\Http\JsonResponse Detail pesanan dalam format JSON.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika pesanan tidak ditemukan.
     */
    public function show(int $id)
    {
        // Cari pesanan berdasarkan ID, lempar exception jika tidak ditemukan
        $order = Order::findOrFail($id);

        // Normalisasi data produk dari order
        // Konversi dari string JSON ke array jika diperlukan
        if(is_string($order->products)) {
            // Decode string JSON menjadi array/objek
            $orderProducts = json_decode($order->products);
        } else {
            // Gunakan data produk asli jika sudah dalam format array/objek
            $orderProducts = $order->products;
        }

        // Ekstrak ID produk dari daftar produk di order
        // Gunakan array_column untuk mengambil kolom 'id' dari array produk
        $productIds = array_column($orderProducts, 'id');

        // Ambil detail produk dari database berdasarkan ID yang didapat
        // Metode whereIn digunakan untuk mencari multiple produk sekaligus
        $products = Product::whereIn('id', $productIds)->get();

        // Siapkan array untuk menyimpan detail produk lengkap
        $result = [];

        // Iterasi setiap produk untuk menambahkan informasi kuantitas
        foreach ($products as $product) {
            // Cari informasi produk spesifik dari order berdasarkan ID
            // Gunakan collect dan firstWhere untuk mencari data yang sesuai
            $orderProduct = (object) collect($orderProducts)->firstWhere('id', $product->id);

            // Gabungkan informasi produk dengan detail dari order
            $result[] = [
                'id' => $product->id,
                'title' => $product->title,
                "image" => $product->image,
                'quantity' => $orderProduct->quantity,
                "subtotal" => rupiah($orderProduct->subtotal)
            ];
        }

        // Susun struktur data lengkap untuk response
        $data = [
            // Informasi ID pesanan
            "id" => $order->id,

            // Daftar produk dalam pesanan
            "products" => $result,

            // Informasi pelanggan
            "customer" => (object) [
                "id" => $order->customer,
                "address" => $order->address
            ],

            // Informasi pembayaran
            "payment" => (object) [
                "total" => rupiah($order->total),
                "method" => $order->payment_method,
                "status" => $order->status
            ]
        ];

        // Kembalikan data dalam format JSON
        // Memudahkan konsumsi data oleh frontend atau API consumer
        return response()->json($data);
    }

    /**
     * Menampilkan halaman edit untuk pesanan tertentu.
     *
     * @description Method ini mengambil detail pesanan berdasarkan ID dan
     *              menyediakan daftar status yang dapat dipilih.
     *
     * @param int $id Identifikasi unik pesanan yang akan diedit.
     * @return \Illuminate\View\View Halaman edit pesanan dengan data terkait.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika pesanan tidak ditemukan.
     */
    public function edit(int $id)
    {
        // Kembalikan view edit dengan data pesanan dan daftar status
        return view("dashboard.orders.edit", [
            // Cari pesanan berdasarkan ID, akan throw exception jika tidak ditemukan
            "order" => Order::findOrFail($id),

            // Ambil daftar status yang valid dari model Order
            "statuses" => Order::statuses()
        ]);
    }

    /**
     * Memperbarui status pesanan.
     *
     * @description Method ini memvalidasi dan memperbarui status pesanan
     *              berdasarkan input yang diberikan.
     *
     * @param \Illuminate\Http\Request $request Data input dari form.
     * @param int $id Identifikasi unik pesanan yang akan diupdate.
     * @return \Illuminate\Http\RedirectResponse Redirect kembali ke halaman edit dengan status.
     *
     * @throws \Illuminate\Validation\ValidationException Jika input tidak valid.
     * @throws \Exception Jika gagal memperbarui pesanan.
     */
    public function update(Request $request, int $id)
    {
        // Definisi pesan error kustom untuk validasi status
        // Memberikan pesan yang lebih informatif jika status tidak valid
        $message = ['status.in' => 'Status yang dipilih tidak valid. Silakan pilih status yang benar.'];

        // Daftar status yang tersedia
        $orderStatuses = implode(',', Order::statuses());

        // Validasi input status
        // Pastikan status yang dipilih ada dalam daftar status yang diizinkan
        $request->validate(["status" => "required|in:" . $orderStatuses], $message);

        try {
            // Cari pesanan berdasarkan ID dan update statusnya
            // Method findOrFail akan melempar exception jika pesanan tidak ditemukan
            Order::findOrFail($id)->update([
                "status" => $request->status
            ]);

            // Redirect kembali ke halaman edit dengan pesan sukses
            // Gunakan withInput() untuk mempertahankan input sebelumnya
            return redirect("dashboard/orders/$id/edit")
                ->with("alert-success", "Pesanan berhasil diperbarui!")
                ->withInput();

        } catch (\Throwable $error) {
            // Tangani kesalahan yang mungkin terjadi saat update
            // Redirect kembali dengan pesan error
            return redirect("dashboard/orders/$id/edit")
                ->with("alert-error", "Pesanan gagal diperbarui! " . $error->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus pesanan berdasarkan ID.
     *
     * @description Method ini mencari dan menghapus pesanan dari database,
     *              kemudian mengarahkan kembali ke daftar pesanan.
     *
     * @param int $id Identifikasi unik pesanan yang akan dihapus.
     * @return \Illuminate\Http\RedirectResponse Redirect ke daftar pesanan dengan status.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika pesanan tidak ditemukan.
     * @throws \Exception Jika gagal menghapus pesanan.
     */
    public function destroy(int $id)
    {
        try {
            // Cari pesanan berdasarkan ID
            // Method findOrFail akan melempar exception jika pesanan tidak ditemukan
            $order = Order::findOrFail($id);

            // Hapus pesanan dari database
            $order->delete();

            // Redirect ke halaman daftar pesanan dengan pesan sukses
            return redirect("dashboard/orders")
                ->with("alert-success", "Pesanan berhasil dihapus!");

        } catch (\Throwable $error) {
            // Tangani kesalahan yang mungkin terjadi saat penghapusan
            // Redirect kembali dengan pesan error
            return redirect("dashboard/orders")
                ->with("alert-error", "Pesanan gagal dihapus! " . $error->getMessage());
        }
    }

    /**
     * Menghasilkan laporan berdasarkan rentang tanggal yang diberikan.
     *
     * @param string $startDate Tanggal mulai untuk laporan dalam format 'Y-m-d'
     * @param string $endDate Tanggal akhir untuk laporan dalam format 'Y-m-d'
     *
     * @return \Illuminate\Http\Response Pengalihan halaman atau tampilan laporan
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses pengambilan data
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil semua pesanan dalam rentang tanggal yang diberikan
     * - Memeriksa apakah ada pesanan yang ditemukan
     * - Menghitung jumlah produk dari semua pesanan
     * - Menyiapkan data untuk tampilan laporan
     *
     * @uses Order Mengambil data pesanan dari database
     */
    public function report(string $startDate, string $endDate)
    {
        // Panggil metode untuk mendapatkan data produk
        $data = $this->getProductData($startDate, $endDate);

        // Jika tidak ada data, alihkan ke halaman 404
        if ($data['orders']->isEmpty()) {
            return redirect("dashboard/orders/404");
        }

        // Tampilkan laporan
        return view("dashboard.orders.contents.report", [
            "orders" => $data['orders'],
            "topProducts" => $data['topProducts'],
            "startDate" => $startDate,
            "endDate" => $endDate
        ]);
    }

    /**
     * Mencetak laporan berdasarkan rentang tanggal yang diberikan.
     *
     * @param string $startDate Tanggal mulai untuk laporan dalam format 'Y-m-d'
     * @param string $endDate Tanggal akhir untuk laporan dalam format 'Y-m-d'
     *
     * @return \Illuminate\Http\Response Unduhan laporan dalam format PDF
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses pengambilan data
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil semua pesanan dalam rentang tanggal yang diberikan
     * - Memeriksa apakah ada pesanan yang ditemukan
     * - Menghitung jumlah produk dari semua pesanan
     * - Menyiapkan data untuk pembuatan laporan PDF
     *
     * @uses Order Mengambil data pesanan dari database
     * @uses Pdf Membuat laporan dalam format PDF
     */
    public function print(string $startDate, string $endDate)
    {
        // Panggil metode untuk mendapatkan data produk
        $data = $this->getProductData($startDate, $endDate);

        // Jika tidak ada data, alihkan ke halaman 404
        if ($data['orders']->isEmpty()) {
            return redirect("dashboard/orders/404");
        }

        // Siapkan data untuk pembuatan laporan PDF
        $pdf = Pdf::loadView("dashboard.orders.contents.report", [
            "orders" => $data['orders'],
            "topProducts" => $data['topProducts'],
            "startDate" => $startDate,
            "endDate" => $endDate
        ])->setPaper("A1", "portrait");

        // Unduh laporan dalam format PDF
        return $pdf->download();
    }

    /**
     * Mengambil data produk berdasarkan rentang tanggal yang diberikan.
     *
     * @param string $startDate Tanggal mulai untuk laporan dalam format 'Y-m-d'
     * @param string $endDate Tanggal akhir untuk laporan dalam format 'Y-m-d'
     *
     * @return array Array yang berisi 'orders' dan 'topProducts'
     *
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses pengambilan data
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil semua pesanan dalam rentang tanggal yang diberikan
     * - Menghitung jumlah produk dari semua pesanan
     * - Mengurutkan produk berdasarkan jumlah terbanyak
     * - Mengembalikan data pesanan dan produk teratas
     *
     * @uses Order Mengambil data pesanan dari database
     */
    private function getProductData(string $startDate, string $endDate)
    {
        // Ambil semua pesanan
        $orders = Order::whereBetween("created_at", [$startDate, $endDate])->get();

        // Inisialisasi array untuk menyimpan jumlah produk
        $productCounts = [];

        foreach ($orders as $order) {
            // Jika $order->products adalah string JSON, dekode menjadi array
            if (is_string($order->products)) {
                $products = json_decode($order->products, true); // Dekode JSON menjadi array
            } else {
                $products = $order->products; // Asumsikan ini sudah array
            }

            // Pastikan $products adalah array sebelum iterasi
            if (is_array($products)) {
                foreach ($products as $product) {

                    // Pastikan $product adalah array yang memiliki kunci 'id' dan 'quantity'
                    $productId = $product["id"];
                    $quantity = $product["quantity"];

                    if (!isset($productCounts[$productId])) {
                        $productCounts[$productId] = 0;
                    }
                    $productCounts[$productId] += $quantity; // Tambahkan kuantitas
                }
            } else {
                // Jika $products bukan array, Anda bisa menangani kesalahan di sini
                Log::error("Expected products to be an array, got: " . gettype($products));
            }
        }

        // Urutkan produk berdasarkan jumlah terbanyak
        arsort($productCounts); // Mengurutkan array berdasarkan nilai

        // Ambil 3 produk teratas
        $topProducts = array_slice($productCounts, 0, 3, true);

        return [
            'orders' => $orders,
            'topProducts' => $topProducts,
        ];
    }


    /**
     * Menampilkan halaman laporan pesanan kosong.
     *
     * @return \Illuminate\View\View Tampilan halaman laporan dengan array pesanan kosong
     *
     * @description Fungsi ini digunakan untuk menampilkan halaman laporan
     * ketika tidak ada pesanan yang ditemukan dalam rentang tanggal tertentu.
     */
    public function notFound()
    {
        return view("dashboard.orders.contents.report", ["orders" => []]);
    }
}
