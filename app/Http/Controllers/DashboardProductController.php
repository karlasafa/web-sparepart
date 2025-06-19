<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardProductController extends Controller
{
    // Aturan untuk melakukan validasi input
    protected $rules = [
        "title" => ["string", "required", "max:100"], // Harus berupa string dan tidak lebih dari 100 karakter
        "category_id" => ["required", "exists:categories,id"], // Harus ada dalam tabel categories
        "price" => ["numeric", "required"], // Harus berupa angka
        "weight" => ["numeric", "required"], // Harus berupa angka
        "stock" => ["numeric", "required"], // Harus berupa angka
        "description" => ["required"] // Harus diisi
    ];

    protected $image_rules = ["image", "file", "max:20000"]; // Harus berupa file gambar dengan ukuran maksimum 20MB
    protected $status_rules = ["required"]; // Harus diisi

    /**
     * Menampilkan daftar produk dengan relasi kategori dan publisher.
     *
     * @return \Illuminate\View\View Halaman daftar produk
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil seluruh produk beserta relasi kategori dan publisher
     * - Menampilkan data produk pada halaman dashboard
     *
     * @uses Product::getAll() Mengambil semua produk dengan relasi terkait
     */
    public function index()
    {
        // Kembalikan view dengan data produk
        return view("dashboard.products.index", ["products" => Product::getAll()]);
    }

    /**
     * Menampilkan formulir pembuatan produk baru.
     *
     * @return \Illuminate\View\View Halaman formulir pembuatan produk
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil seluruh kategori untuk dropdown
     * - Mengambil seluruh pengguna sebagai calon publisher
     * - Menampilkan formulir pembuatan produk
     *
     * @uses Category::all() Mengambil seluruh kategori
     * @uses User::all() Mengambil seluruh pengguna
     *
     */
    public function create()
    {
        // @note Siapkan data kategori dan pengguna untuk formulir
        return view("dashboard.products.create", [
            "categories" => Category::all(),
            "publishers" => User::all()
        ]);
    }

    /**
     * Menyimpan produk baru ke dalam database.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data input produk
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan sukses atau kesalahan
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses penyimpanan
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input yang diterima
     * - Menambahkan ID publisher (pengguna yang sedang login)
     * - Menangani unggah file gambar produk
     * - Menyimpan produk baru ke dalam database
     *
     * @uses Validator Melakukan validasi input
     * @uses Auth Mengambil informasi pengguna yang sedang login
     *
     * @todo Pertimbangkan untuk menambahkan log aktivitas
     */
    public function store(Request $request)
    {
        // Tambahkan aturan validasi untuk gambar jika file gambar diunggah
        if ($request->file("image")) {

            // @note Menambahkan aturan validasi khusus untuk gambar
            $this->rules["image"] = $this->image_rules;
        }

        // Validasi semua input yang diterima
        $validator = Validator::make($request->all(), $this->rules);

        // Tangani kegagalan validasi
        if ($validator->fails()) {

            // @note Kembalikan ke halaman create dengan pesan kesalahan dan input sebelumnya
            return redirect("dashboard/products/create")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $product = $validator->validated();

        // @note Tambahkan ID pengguna yang sedang login sebagai publisher
        $product["publisher"] = Auth::user()->id;

        // Proses unggah gambar jika ada file gambar
        if ($request->file("image")) {

            // @note Simpan file gambar dan update path gambar dalam data produk
            $product["image"] = $request->file("image")->store("product-images", "public");
        }

        try {
            // Buat produk baru di database
            Product::create($product);

            // @note Redirect dengan pesan sukses, gambar sebelumnya, dan input
            return redirect("dashboard/products/create")
                ->with("alert-success", "Data created successfully!")
                ->with("prev-image", $product["image"])
                ->withInput();
        } catch (\Throwable $th) {

            // @note Tangani kesalahan saat penyimpanan dan redirect dengan pesan error
            return redirect("dashboard/products/create")
                ->with("alert-error", "Data failed to create!" . $th->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail produk berdasarkan ID.
     *
     * @param int $id Identifikasi unik produk
     *
     * @return \Illuminate\Http\JsonResponse Respon JSON detail produk
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika produk tidak ditemukan
     *
     * @description Fungsi ini melakukan proses:
     * - Mencari produk berdasarkan ID dengan relasi gambar
     * - Mengembalikan data produk dalam format JSON
     *
     * @uses Product::with() Mengambil produk dengan relasi gambar
     * @uses Product::findOrFail() Mencari produk atau lempar exception
     *
     */
    public function show(int $id)
    {
        // @note Cari produk dengan relasi gambar
        $product = Product::with("images")->findOrFail($id);

        // @note Kembalikan data produk dalam format JSON
        return response()->json($product);
    }


    /**
     * Menampilkan formulir edit produk.
     *
     * @param int $id Identifikasi unik produk
     *
     * @return \Illuminate\View\View Halaman formulir edit produk
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika produk tidak ditemukan
     *
     * @description Fungsi ini melakukan proses:
     * - Mencari produk berdasarkan ID
     * - Mengambil seluruh kategori untuk dropdown
     * - Mengambil daftar status produk
     * - Menampilkan formulir edit produk
     *
     * @uses Product::findOrFail() Mencari produk atau lempar exception
     * @uses Category::all() Mengambil seluruh kategori
     * @uses Product::statuses() Mengambil daftar status produk
     *
     */
    public function edit(int $id)
    {
        // @note Siapkan data produk, kategori, dan status untuk formulir edit
        return view("dashboard.products.edit", [
            "product" => Product::findOrFail($id),
            "categories" => Category::all(),
            "statuses" => Product::statuses()
        ]);
    }

    /**
     * Memperbarui data produk berdasarkan ID yang diberikan.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data input
     * @param int $id Identifikasi unik produk yang akan diperbarui
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan sukses atau kesalahan
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses pembaruan
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input yang diterima
     * - Menangani upload file gambar (jika ada)
     * - Memperbarui data produk di database
     * - Mengelola penghapusan gambar lama
     *
     * @uses Validator Melakukan validasi input
     * @uses removeFromStorage() Menghapus file dari penyimpanan
     *
     * @todo Pertimbangkan untuk menambahkan log kesalahan
     */
    public function update(Request $request, int $id)
    {
        // Tambahkan aturan validasi untuk gambar jika file gambar diunggah
        if ($request->file("image")) {

            // @note Menambahkan aturan validasi khusus untuk gambar
            $this->rules["image"] = $this->image_rules;
        }

        // @note Tambahkan aturan validasi untuk status
        $this->rules["status"] = $this->status_rules;

        // Validasi semua input yang diterima
        $validator = Validator::make($request->all(), $this->rules);

        // Tangani kegagalan validasi
        if ($validator->fails()) {

            // @note Kembalikan ke halaman edit dengan pesan kesalahan dan input sebelumnya
            return redirect("dashboard/products/$id/edit")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $data = $validator->validated();

        // Cari produk berdasarkan ID
        $product = Product::find($id);

        // Proses unggah gambar jika ada file gambar baru
        if ($request->file("image")) {

            // Hapus file gambar lama
            removeFromStorage($product->image);

            // @note Simpan file gambar baru dan update path gambar dalam data
            $data["image"] = $request->file("image")->store("product-images", "public");
        }

        try {
            // Perbarui data produk
            $product->update($data);

            // @note Redirect dengan pesan sukses, gambar sebelumnya, dan input
            return redirect("dashboard/products/$id/edit")
                ->with("alert-success", "Data updated successfully!")
                ->with("prev-image", $product["image"])
                ->withInput();
        } catch (\Throwable $error) {

            // @note Tangani kesalahan saat pembaruan dan redirect dengan pesan error
            return redirect("dashboard/products/$id/edit")
                ->with("alert-error", "Data failed to update!" . $error->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus produk berdasarkan ID yang diberikan.
     *
     * @param int $id Identifikasi unik produk yang akan dihapus
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan sukses atau kesalahan
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Jika produk dengan ID tidak ditemukan
     * @throws \Exception Kesalahan umum yang mungkin terjadi selama proses penghapusan
     *
     * @description Fungsi ini melakukan proses:
     * - Mencari produk berdasarkan ID
     * - Menghapus produk dari database
     * - Menghapus file gambar terkait dari penyimpanan
     * - Menangani kesalahan yang mungkin terjadi selama proses
     *
     * @uses removeFromStorage() Fungsi untuk menghapus file dari penyimpanan
     * @todo Pertimbangkan untuk menambahkan log kesalahan
     */
    public function destroy(int $id)
    {
        // Cari produk atau lemparkan exception jika tidak ditemukan
        $product = Product::findOrFail($id);

        try {
            // Hapus produk dari database
            $product->delete();

            // Hapus file gambar terkait dari penyimpanan
            removeFromStorage($product->image);

            // Redirect dengan pesan sukses
            return redirect("dashboard/products")->with("alert-success", "Data deleted successfully!");
        } catch (\Throwable $error) {

            // Tangani kesalahan dan redirect dengan pesan error
            return redirect("dashboard/products")->with("alert-error", "Data failed to delete!" . $error->getMessage());
        }
    }

    /**
     * Menghasilkan laporan produk berdasarkan rentang tanggal tertentu.
     *
     * @param string $startDate Tanggal mulai untuk filter laporan (format: Y-m-d)
     * @param string $endDate Tanggal akhir untuk filter laporan (format: Y-m-d)
     *
     * @return \Illuminate\Http\Response Respon unduhan PDF laporan produk atau pengalihan halaman
     *
     * @throws \Exception Kesalahan yang mungkin terjadi selama pembuatan laporan
     *
     * @description Fungsi ini melakukan proses:
     * - Memfilter produk berdasarkan rentang tanggal yang diberikan
     * - Membatasi akses sesuai peran pengguna
     * - Mengambil data produk beserta kategorinya
     * - Menghasilkan laporan PDF atau mengarahkan ke halaman 404 jika tidak ada data
     *
     * @uses Auth::user() Mendapatkan informasi pengguna yang sedang login
     * @uses Product::whereBetween() Menyaring produk berdasarkan rentang tanggal
     * @uses Pdf::loadView() Membuat dokumen PDF dari view yang ditentukan
     */
    public function report(string $startDate, string $endDate)
    {
        // Mulai query untuk menyaring produk berdasarkan rentang tanggal
        $products = Product::whereBetween("created_at", [$startDate, $endDate]);

        // Batasi akses produk berdasarkan peran pengguna
        // Jika bukan SuperAdmin, hanya tampilkan produk milik pengguna yang sedang login
        if (Auth::user()->role !== "SuperAdmin") {
            $products = $products->where("publisher", "=", Auth::user()->id);
        }

        // Ambil data produk dengan relasi kategori
        $products = $products->with("category")->get();

        // Periksa apakah tidak ada produk yang ditemukan
        if ($products->isEmpty()) {
            // Alihkan ke halaman 404 jika tidak ada data produk
            return redirect("dashboard/products/404");
        }

        // Siapkan data untuk pembuatan laporan PDF
        $pdf = Pdf::loadView("dashboard.products.contents.report", [
            "products" => $products, // Data produk yang akan ditampilkan
            "startDate" => $startDate, // Tanggal mulai laporan
            "endDate" => $endDate // Tanggal akhir laporan
        ]);

        // Unduh laporan dalam format PDF
        return $pdf->download();
    }

    /**
     * Menampilkan halaman laporan produk kosong.
     *
     * @return \Illuminate\View\View Tampilan halaman laporan dengan array produk kosong
     *
     * @description Fungsi ini digunakan untuk menampilkan halaman laporan
     * ketika tidak ada produk yang ditemukan dalam rentang tanggal tertentu.
     */
    public function notFound()
    {
        return view("dashboard.products.contents.report", ["products" => []]);
    }
}
