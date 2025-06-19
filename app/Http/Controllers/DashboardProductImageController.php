<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardProductImageController extends Controller
{
    // Aturan validasi untuk ID produk
    protected $rules = [
        "product_id" => ["required", "exists:products,id"] // product_id wajib diisi dan harus ada di tabel products
    ];

    // Aturan validasi untuk unggahan gambar
    protected $source_rules = [
        "image", // Harus berupa file gambar
        "file", // Harus berupa file
        "max:20000", // Ukuran maksimal file 20MB (20000 KB)
        "required" // File wajib diunggah
    ];

    /**
     * Menampilkan daftar gambar produk.
     *
     * @return \Illuminate\View\View Tampilan daftar gambar produk
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil sumber gambar produk menggunakan metode getSources()
     * - Mengirim data ke view index gambar produk
     *
     * @uses ProductImage::getSources() Mengambil gambar produk sesuai otorisasi
     */
    public function index()
    {
        // Render view index dengan daftar gambar produk
        return view("dashboard.product-images.index", [
            "productImages" => ProductImage::getSources()
        ]);
    }

    /**
     * Menampilkan formulir pembuatan gambar produk baru.
     *
     * @return \Illuminate\View\View Tampilan formulir pembuatan gambar produk
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil daftar produk menggunakan metode getAll()
     * - Mengirim data produk ke view formulir pembuatan gambar
     *
     * @uses Product::getAll() Mengambil daftar semua produk
     */
    public function create()
    {
        // Render view create dengan daftar produk
        return view("dashboard.product-images.create", [
            "products" => Product::getAll()
        ]);
    }

    /**
     * Menyimpan gambar produk baru ke dalam penyimpanan.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data input gambar produk
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan sukses atau kesalahan
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Throwable Kesalahan umum yang mungkin terjadi selama proses penyimpanan
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input yang diterima
     * - Menangani unggah file gambar produk
     * - Menyimpan gambar produk baru ke dalam database
     *
     * @uses Validator Melakukan validasi input
     * @uses ProductImage Model untuk menyimpan gambar produk
     * @uses Auth Mengambil informasi pengguna yang sedang login
     *
     * @todo Pertimbangkan untuk menambahkan log aktivitas
     */
    public function store(Request $request)
    {
        // Tambahkan aturan validasi untuk unggahan sumber gambar
        $this->rules["source"] = $this->source_rules;

        // Validasi semua input yang dikirim melalui formulir
        $validator = Validator::make($request->all(), $this->rules);

        // Periksa apakah validasi gagal
        if ($validator->fails()) {

            // Kembalikan ke halaman formulir dengan pesan kesalahan validasi
            return redirect("dashboard/product-images/create")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $data = $validator->validated();

        // Proses unggahan file gambar jika ada
        if ($request->file("source")) {

            // Simpan file gambar ke direktori penyimpanan
            $data["source"] = $request->file("source")->store("product-images", "public");
        }

        try {
            // Buat record baru di database menggunakan data yang sudah divalidasi
            ProductImage::create($data);

            // Redirect ke halaman formulir dengan pesan sukses
            return redirect("dashboard/product-images/create")
                ->with("alert-success", "Data created successfully!")
                ->with("prev-image", $data["source"])
                ->withInput();
        } catch (\Throwable $th) {

            // Tangani kesalahan saat pembuatan record dan redirect dengan pesan kesalahan
            return redirect("dashboard/product-images/create")
                ->with("alert-error", "Data failed to create! " . $th->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan formulir untuk mengedit gambar produk yang ditentukan.
     *
     * @param int $id ID gambar produk yang akan diedit
     *
     * @return \Illuminate\View\View Tampilan formulir edit gambar produk
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil gambar produk berdasarkan ID
     * - Mengambil daftar produk untuk dropdown
     *
     * @uses ProductImage::findOrFail() Mengambil gambar produk berdasarkan ID
     * @uses Product::getAll() Mengambil daftar semua produk
     */
    public function edit(int $id)
    {
        // Render view edit dengan data gambar produk dan daftar produk
        return view("dashboard.product-images.edit", [
            "productImage" => ProductImage::findOrFail($id), // Cari gambar produk berdasarkan ID atau tampilkan error 404
            "products" => Product::getAll() // Ambil daftar semua produk untuk dropdown
        ]);
    }

    /**
     * Memperbarui gambar produk yang ditentukan dalam penyimpanan.
     *
     * @param \Illuminate\Http\Request $request Objek request yang berisi data input untuk pembaruan
     * @param int $id ID gambar produk yang akan diperbarui
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan sukses atau kesalahan
     *
     * @throws \Illuminate\Validation\ValidationException Kesalahan validasi input
     * @throws \Throwable Kesalahan umum yang mungkin terjadi selama proses pembaruan
     *
     * @description Fungsi ini melakukan proses:
     * - Validasi input yang diterima
     * - Menangani unggah file gambar baru jika ada
     * - Memperbarui gambar produk dalam database
     *
     * @uses Validator Melakukan validasi input
     * @uses ProductImage::find() Mencari gambar produk berdasarkan ID
     */
    public function update(Request $request, int $id)
    {
        // Periksa apakah terdapat file yang diunggah
        if ($request->file("source")) {

            // Tambahkan aturan validasi khusus untuk file sumber jika ada file yang diunggah
            $this->rules["source"] = $this->source_rules;
        }

        // Lakukan validasi untuk semua input yang dikirim melalui formulir
        $validator = Validator::make($request->all(), $this->rules);

        // Periksa apakah validasi gagal
        if ($validator->fails()) {

            // Kembalikan ke halaman formulir edit dengan pesan kesalahan validasi dan input sebelumnya
            return redirect("dashboard/product-images/$id/edit")
                ->withErrors($validator->errors())
                ->withInput();
        }

        // Ambil data yang sudah tervalidasi
        $data = $validator->validated();

        // Cari data gambar produk berdasarkan ID yang dikirim melalui URL
        $productImage = ProductImage::find($id);

        // Periksa apakah ada file baru yang diunggah
        if ($request->file("source")) {

            // Hapus file gambar lama dari penyimpanan
            removeFromStorage($productImage->source);

            // Simpan file gambar baru dan perbarui path sumber dalam data
            $data["source"] = $request->file("source")->store("product-images", "public");
        }

        try {
            // Perbarui data gambar produk dengan data yang sudah tervalidasi
            $productImage->update($data);

            // Kembalikan ke halaman formulir edit dengan pesan sukses
            return redirect("dashboard/product-images/$id/edit")
                ->with("alert-success", "Data updated successfully!")
                ->with("prev-image", $productImage["source"])
                ->withInput();
        } catch (\Throwable $error) {

            // Tangani kesalahan saat pembaruan dan kembalikan ke halaman formulir edit
            return redirect("dashboard/product-images/$id/edit")
                ->with("alert-error", "Data failed to update!" . $error->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus gambar produk yang ditentukan dari penyimpanan.
     *
     * @param int $id ID gambar produk yang akan dihapus
     *
     * @return \Illuminate\Http\RedirectResponse Pengalihan halaman dengan pesan sukses atau kesalahan
     *
     * @throws \Throwable Kesalahan umum yang mungkin terjadi selama proses penghapusan
     *
     * @description Fungsi ini melakukan proses:
     * - Mengambil gambar produk berdasarkan ID
     * - Menghapus gambar produk dari database dan penyimpanan
     *
     * @uses ProductImage::findOrFail() Mengambil gambar produk berdasarkan ID
     */
    public function destroy(int $id)
    {
        // Cari gambar produk berdasarkan ID atau tampilkan error 404
        $productImage = ProductImage::findOrFail($id);

        try {
            // Hapus data gambar produk dari database
            $productImage->delete();

            // Hapus file gambar dari penyimpanan
            removeFromStorage($productImage->source);

            // Kembalikan ke halaman daftar gambar produk dengan pesan sukses
            return redirect("dashboard/product-images")
                ->with("alert-success", "Data deleted successfully!");
        } catch (\Throwable $error) {

            // Tangani kesalahan saat penghapusan dan kembalikan ke halaman daftar gambar produk
            return redirect("dashboard/product-images")
                ->with("alert-error", "Data failed to delete!" . $error->getMessage());
        }
    }
}
